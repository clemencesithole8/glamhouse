<?php

namespace App\Support;

class PublicBusinessContact
{
    public static function details(): array
    {
        $business = config('seo.business', []);

        $name = self::clean($business['name'] ?? config('seo.site_name', config('app.name', 'Glamhouse')));
        $phone = self::clean($business['phone'] ?? '');
        $phoneDigits = preg_replace('/\D+/', '', $phone) ?: '';
        $email = self::clean($business['email'] ?? '');
        $streetAddress = self::clean($business['street_address'] ?? '');
        $locality = self::clean($business['locality'] ?? '');
        $region = self::clean($business['region'] ?? '');
        $postalCode = self::clean($business['postal_code'] ?? '');
        $countryCode = self::clean($business['country'] ?? '');
        $countryName = self::clean($business['country_name'] ?? '') ?: self::countryDisplay($countryCode);
        $serviceMode = self::clean($business['service_mode'] ?? '') ?: 'Studio + Outcall';

        $locationDisplay = implode(', ', self::filledUnique([$locality, $countryName ?: $countryCode]));
        $serviceLocationLabel = self::clean($business['service_location_label'] ?? '');
        if ($serviceLocationLabel === '') {
            $serviceLocationLabel = $locality !== '' ? "{$locality} studio + outcall" : $serviceMode;
        }

        $whatsappMessage = self::clean($business['whatsapp_prefill'] ?? '');
        if ($whatsappMessage === '') {
            $locationPhrase = $locality !== '' ? " in {$locality}" : '';
            $whatsappMessage = "Hi {$name}, I found you on Google and would like to book a makeup appointment{$locationPhrase}.";
        }

        $mapsQuery = self::clean($business['maps_query'] ?? '');
        if ($mapsQuery === '') {
            $mapsQuery = implode(' ', self::filledUnique([
                $name,
                $streetAddress,
                $locality,
                $region,
                $postalCode,
                $countryName ?: $countryCode,
            ]));
        }

        return [
            'business_name' => $name,
            'phone' => $phone,
            'phone_digits' => $phoneDigits,
            'phone_display' => self::phoneDisplay($phone, $phoneDigits),
            'has_phone' => $phoneDigits !== '',
            'whatsapp_message' => $whatsappMessage,
            'whatsapp_url' => $phoneDigits !== ''
                ? 'https://wa.me/'.$phoneDigits.'?text='.rawurlencode($whatsappMessage)
                : '',
            'email' => $email,
            'email_url' => $email !== '' ? 'mailto:'.$email : '',
            'has_email' => $email !== '',
            'street_address' => $streetAddress,
            'locality' => $locality,
            'region' => $region,
            'postal_code' => $postalCode,
            'country' => $countryCode,
            'country_name' => $countryName,
            'location_display' => $locationDisplay,
            'location_label' => $locality !== '' ? "{$locality} Location" : 'Location',
            'service_mode' => $serviceMode,
            'service_location_label' => $serviceLocationLabel,
            'maps_query' => $mapsQuery,
            'maps_url' => $mapsQuery !== ''
                ? 'https://www.google.com/maps/search/?api=1&query='.rawurlencode($mapsQuery)
                : '',
        ];
    }

    private static function clean(mixed $value): string
    {
        return trim((string) $value);
    }

    private static function phoneDisplay(string $phone, string $phoneDigits): string
    {
        if ($phone !== '') {
            return str_starts_with($phone, '+') || $phoneDigits === '' ? $phone : '+'.$phoneDigits;
        }

        return $phoneDigits !== '' ? '+'.$phoneDigits : '';
    }

    private static function countryDisplay(string $country): string
    {
        return match (strtoupper($country)) {
            'ZW' => 'Zimbabwe',
            default => $country,
        };
    }

    private static function filledUnique(array $values): array
    {
        $filled = [];

        foreach ($values as $value) {
            $cleanValue = self::clean($value);
            $key = strtolower($cleanValue);

            if ($cleanValue !== '' && ! array_key_exists($key, $filled)) {
                $filled[$key] = $cleanValue;
            }
        }

        return array_values($filled);
    }
}
