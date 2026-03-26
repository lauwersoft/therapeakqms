<?php

namespace App\Jobs;

use App\Models\UserActivity;
use GeoIp2\Database\Reader;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class TrackUserActionJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private int $userId,
        private string $type,
        private string $path,
        private ?string $docId = null,
        private ?string $docTitle = null,
        private ?string $detail = null,
        private ?string $ip = null,
    ) {}

    public function handle(): void
    {
        $data = [
            'user_id' => $this->userId,
            'type' => $this->type,
            'path' => $this->path,
            'doc_id' => $this->docId,
            'doc_title' => $this->docTitle,
            'detail' => $this->detail,
            'ip' => $this->ip,
        ];

        if ($this->ip && !in_array($this->ip, ['127.0.0.1', '::1'])) {
            try {
                $reader = new Reader(base_path('geoip/GeoLite2-Country.mmdb'));
                $data['country_code'] = $reader->country($this->ip)->country->isoCode;
            } catch (\Throwable $e) {}

            try {
                $reader = new Reader(base_path('geoip/GeoLite2-ASN.mmdb'));
                $asn = $reader->asn($this->ip);
                $data['asn_number'] = $asn->autonomousSystemNumber;
                $data['asn_org'] = $asn->autonomousSystemOrganization;
            } catch (\Throwable $e) {}
        }

        UserActivity::create($data);
    }
}
