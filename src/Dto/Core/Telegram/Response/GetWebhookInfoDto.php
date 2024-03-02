<?php

namespace App\Dto\Core\Telegram\Response;

use App\Service\System\HttpClient\Response\Response;

class GetWebhookInfoDto extends Response // todo перенести в App\Service\System\HttpClient\Response
{
    private string $url;

    private bool $has_custom_certificate;

    private ?int $pending_update_count = null;

    private ?int $max_connections = null;

    private ?string $ip_address = null;

    private ?int $last_error_date = null;

    private ?string $last_error_message = null;

    private ?int $last_synchronization_error_date = null;

    private ?array $allowed_updates = null;

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function isHasCustomCertificate(): bool
    {
        return $this->has_custom_certificate;
    }

    public function setHasCustomCertificate(bool $has_custom_certificate): void
    {
        $this->has_custom_certificate = $has_custom_certificate;
    }

    public function getPendingUpdateCount(): ?int
    {
        return $this->pending_update_count;
    }

    public function setPendingUpdateCount(int $pending_update_count): void
    {
        $this->pending_update_count = $pending_update_count;
    }

    public function getMaxConnections(): ?int
    {
        return $this->max_connections;
    }

    public function setMaxConnections(?int $max_connections): void
    {
        $this->max_connections = $max_connections;
    }

    public function getIpAddress(): ?string
    {
        return $this->ip_address;
    }

    public function setIpAddress(?string $ip_address): void
    {
        $this->ip_address = $ip_address;
    }

    public function getLastErrorDate(): ?int
    {
        return $this->last_error_date;
    }

    public function setLastErrorDate(?int $last_error_date): void
    {
        $this->last_error_date = $last_error_date;
    }

    public function getLastErrorMessage(): ?string
    {
        return $this->last_error_message;
    }

    public function setLastErrorMessage(?string $last_error_message): void
    {
        $this->last_error_message = $last_error_message;
    }

    public function getLastSynchronizationErrorDate(): ?int
    {
        return $this->last_synchronization_error_date;
    }

    public function setLastSynchronizationErrorDate(?int $last_synchronization_error_date): void
    {
        $this->last_synchronization_error_date = $last_synchronization_error_date;
    }

    public function getAllowedUpdates(): ?array
    {
        return $this->allowed_updates;
    }

    public function setAllowedUpdates(?array $allowed_updates): void
    {
        $this->allowed_updates = $allowed_updates;
    }
}
