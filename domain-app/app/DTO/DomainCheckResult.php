<?php
namespace App\DTO;
// Это контейнер для данных с доменами
class DomainCheckResult {
    public function __construct(
        public string $domain,
        public bool $isAvailable,
        public ?string $expiresAt = null
    ){}
    public function toArray(): array
    {
        return [
            'domain' => $this->domain,
            'is_registered' => $this->isAvailable,
            'expires_at' => $this->expiresAt,
        ];
    }
}
