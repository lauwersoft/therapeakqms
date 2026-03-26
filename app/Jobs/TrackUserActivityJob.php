<?php

namespace App\Jobs;

use App\Models\UserActivity;
use GeoIp2\Database\Reader;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;

class TrackUserActivityJob implements ShouldQueue
{
    use Queueable;

    public function __construct(private array $data) {}

    public function handle(): void
    {
        $geo = $this->geoLookup($this->data['ip'] ?? '');
        $this->data['country_code'] = $geo['country_code'];
        $this->data['city'] = $geo['city'];
        $this->data['asn_number'] = $geo['asn_number'];
        $this->data['asn_org'] = $geo['asn_org'];

        UserActivity::create($this->data);
    }

    private function geoLookup(string $ip): array
    {
        $result = ['country_code' => null, 'city' => null, 'asn_number' => null, 'asn_org' => null];

        if (in_array($ip, ['127.0.0.1', '::1', ''])) {
            return $result;
        }

        try {
            $reader = new Reader(base_path('geoip/GeoLite2-Country.mmdb'));
            $result['country_code'] = $reader->country($ip)->country->isoCode;
        } catch (\Throwable $e) {}

        try {
            $reader = new Reader(base_path('geoip/GeoLite2-ASN.mmdb'));
            $asn = $reader->asn($ip);
            $result['asn_number'] = $asn->autonomousSystemNumber;
            $result['asn_org'] = $asn->autonomousSystemOrganization;
        } catch (\Throwable $e) {}

        // City lookup via API (cached per IP for 7 days, runs async in queue)
        $result['city'] = Cache::remember('geocity:' . $ip, 604800, function () use ($ip) {
            try {
                $response = file_get_contents("http://ip-api.com/json/{$ip}?fields=city", false,
                    stream_context_create(['http' => ['timeout' => 3]]));
                $data = json_decode($response, true);
                return $data['city'] ?? null;
            } catch (\Throwable $e) {
                return null;
            }
        });

        return $result;
    }
}
