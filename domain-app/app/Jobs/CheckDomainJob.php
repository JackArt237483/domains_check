<?php

namespace App\Jobs;

use App\DTO\DomainCheckResult;
use App\Models\Domain;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Validator;

class CheckDomainJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(protected string $domain)
    {
    }

    public function handle()
    {
        $validator = Validator::make(['domain' => $this->domain], [
            'domain' => 'required|regex:/^[a-zA-Z0-9][a-zA-Z0-9-]{0,61}[a-zA-Z0-9]\.[a-zA-Z]{2,}$/'
        ]);

        if ($validator->fails()) {
            return new DomainCheckResult($this->domain, false);
        }

        $domainRecord = Domain::where('name', $this->domain)->first();

        return new DomainCheckResult(
            domain: $this->domain,
            isAvailable: !$domainRecord || !$domainRecord->is_registered,
            expiresAt: $domainRecord?->expires_at?->toDateString()
        );
    }
}
