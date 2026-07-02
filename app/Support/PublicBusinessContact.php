<?php

namespace App\Support;

class PublicBusinessContact
{
    public static function details(): array
    {
        $business = config('seo.business', []);

        $name = self::clean(business_setting('name', $business['name'] ?? config('seo.site_name', config('app.name', 'Glamhouse'))));
        $phone = self::clean(business_setting('phone', $business['phone'] ?? ''));
        $phoneDigits = preg_replace('/\D+/', '', $phone) ?: '';
        $email = self::clean(business_setting('email', $business['email'] ?? ''));
        $streetAddress = self::clean(business_setting('street_address', $business['street_address'] ?? ''));
        $locality = self::clean(business_setting('locality', $business['locality'] ?? ''));
        $region = self::clean(business_setting('region', $business['region'] ?? ''));
        $postalCode = self::clean(business_setting('postal_code', $business['postal_code'] ?? ''));
        $countryCode = self::clean(business_setting('country', $business['country'] ?? ''));
        $countryName = self::clean(business_setting('country_name', $business['country_name'] ?? '')) ?: self::countryDisplay($countryCode);
        $serviceMode = self::clean(business_setting('service_mode', $business['service_mode'] ?? '')) ?: 'Studio + Outcall';
        $locationOverride = self::clean(setting_value('business_location', $business['location'] ?? ''));

        $locationDisplay = $locationOverride !== ''
            ? $locationOverride
            : implode(', ', self::filledUnique([$locality, $countryName ?: $countryCode]));
        $serviceLocationLabel = self::clean($business['service_location_label'] ?? '');
        if ($serviceLocationLabel === '') {
            $serviceLocationLabel = $locationOverride !== ''
                ? $locationOverride
                : ($locality !== '' ? "{$locality} studio + outcall" : $serviceMode);
        }

        $whatsappMessage = self::clean(business_setting('whatsapp_prefill', $business['whatsapp_prefill'] ?? ''));
        if ($whatsappMessage === '') {
            $locationPhrase = $locality !== '' ? " in {$locality}" : '';
            $whatsappMessage = "Hi {$name}, I found you on Google and would like to book a makeup appointment{$locationPhrase}.";
        }

        $mapsQuery = self::clean(business_setting('maps_query', $business['maps_query'] ?? ''));
        $mapsUrl = self::clean(business_setting('maps_url', $business['maps_url'] ?? ''));
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
            'maps_url' => $mapsUrl !== ''
                ? $mapsUrl
                : ($mapsQuery !== ''
                ? 'https://www.google.com/maps/search/?api=1&query='.rawurlencode($mapsQuery)
                : ''),
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
