<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;

class TwoFactorAuthenticator
{
    private const ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    public function generateSecret(int $bytes = 20): string
    {
        return $this->base32Encode(random_bytes($bytes));
    }

    public function verify(string $secret, string $code, int $window = 1): bool
    {
        $code = preg_replace('/\s+/', '', $code);

        if (! is_string($code) || ! preg_match('/^\d{6}$/', $code)) {
            return false;
        }

        $timestamp = time();

        for ($offset = -$window; $offset <= $window; $offset++) {
            if (hash_equals($this->codeAt($secret, $timestamp + ($offset * 30)), $code)) {
                return true;
            }
        }

        return false;
    }

    public function otpauthUri(User $user, string $secret): string
    {
        $issuer = config('seo.site_name', config('app.name', 'Glamhouse'));
        $label = rawurlencode($issuer.':'.$user->email);

        return 'otpauth://totp/'.$label.'?secret='.$secret.'&issuer='.rawurlencode($issuer).'&digits=6&period=30';
    }

    public function generateRecoveryCodes(int $count = 8): array
    {
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $codes = [];

        for ($i = 0; $i < $count; $i++) {
            $left = '';
            $right = '';

            for ($j = 0; $j < 5; $j++) {
                $left .= $alphabet[random_int(0, strlen($alphabet) - 1)];
                $right .= $alphabet[random_int(0, strlen($alphabet) - 1)];
            }

            $codes[] = $left.'-'.$right;
        }

        return $codes;
    }

    private function codeAt(string $secret, int $timestamp): string
    {
        $key = $this->base32Decode($secret);
        $counter = intdiv($timestamp, 30);
        $binaryCounter = pack('N*', 0).pack('N*', $counter);
        $hash = hash_hmac('sha1', $binaryCounter, $key, true);
        $offset = ord($hash[strlen($hash) - 1]) & 0x0f;
        $value = ((ord($hash[$offset]) & 0x7f) << 24)
            | ((ord($hash[$offset + 1]) & 0xff) << 16)
            | ((ord($hash[$offset + 2]) & 0xff) << 8)
            | (ord($hash[$offset + 3]) & 0xff);

        return Str::padLeft((string) ($value % 1000000), 6, '0');
    }

    private function base32Encode(string $bytes): string
    {
        $bits = '';
        $encoded = '';

        foreach (str_split($bytes) as $byte) {
            $bits .= Str::padLeft(decbin(ord($byte)), 8, '0');
        }

        foreach (str_split($bits, 5) as $chunk) {
            $chunk = Str::padRight($chunk, 5, '0');
            $encoded .= self::ALPHABET[bindec($chunk)];
        }

        return $encoded;
    }

    private function base32Decode(string $secret): string
    {
        $secret = strtoupper(preg_replace('/[^A-Z2-7]/', '', $secret));
        $bits = '';
        $decoded = '';

        foreach (str_split($secret) as $char) {
            $value = strpos(self::ALPHABET, $char);

            if ($value === false) {
                continue;
            }

            $bits .= Str::padLeft(decbin($value), 5, '0');
        }

        foreach (str_split($bits, 8) as $chunk) {
            if (strlen($chunk) === 8) {
                $decoded .= chr(bindec($chunk));
            }
        }

        return $decoded;
    }
}
