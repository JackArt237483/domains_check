<?php

namespace App\Services;

use App\DTO\DomainCheckResult;
use App\Models\Domain;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DomainCheckerService
{
    public function checkDomains(array $domains): array
    {
        $domains = array_unique(array_map('trim', $domains));
        $results = [];

        foreach ($domains as $domain) {
            $validator = Validator::make(['domain' => $domain], [
                'domain' => [
                    'required',
                    'regex:/^[a-zA-Z0-9][a-zA-Z0-9\-]{0,61}[a-zA-Z0-9]\.[a-zA-Z]{2,}$/'
                ],
            ]);

            if ($validator->fails()) {
                Log::warning('Невалидный домен: ' . $domain);
                $results[] = new DomainCheckResult($domain, null);
                continue;
            }

            $domainRecord = Domain::where('name', $domain)->first();

            if ($domainRecord && $domainRecord->is_registered) {
                $results[] = new DomainCheckResult(
                    domain: $domain,
                    isAvailable: false,
                    expiresAt: $domainRecord->expires_at?->toDateString()
                );
            } else {
                $results[] = new DomainCheckResult(
                    domain: $domain,
                    isAvailable: true
                );
            }
        }

        return $results;
    }
}