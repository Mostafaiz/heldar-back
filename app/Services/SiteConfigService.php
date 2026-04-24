<?php

namespace App\Services;

use App\Models\SiteConfig;

class SiteConfigService
{
    public function updateGeneralData(array $data): void
    {
        $config = SiteConfig::firstOrCreate([]);

        $config->update([
            'admin_phone' => $data['phone'] ?? null,
            'admin_address' => $data['address'] ?? null,
            'about_us' => $data['aboutUs'] ?? null,
        ]);
    }

    public function updateSMSPhones(array $data): void
    {
        $config = SiteConfig::firstOrCreate([]);

        $config->update([
            'first_sms_phone' => $data['firstSMSPhone'] ?? null,
            'second_sms_phone' => $data['secondSMSPhone'] ?? null,
        ]);
    }

    public function getData()
    {
        return SiteConfig::first(['admin_phone', 'admin_address', 'about_us', 'first_sms_phone', 'second_sms_phone'])?->toArray();
    }

    public function get(): ?SiteConfig
    {
        return cache()->rememberForever('site_config', function () {
            return SiteConfig::first();
        });
    }
}
