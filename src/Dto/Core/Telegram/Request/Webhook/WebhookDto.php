<?php

namespace App\Dto\Core\Telegram\Request\Webhook;

class WebhookDto
{
    private string $url;

    private $certificate; // todo insert type !!!

    private ?string $ip_address;

    private ?int $max_connections;

    private array|string|null $allowed_updates;

    private ?bool $drop_pending_updates;

    private ?string $secret_token;

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getCertificate()
    {
        return $this->certificate;
    }

    public function setCertificate($certificate): self
    {
        $this->certificate = $certificate;

        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ip_address;
    }

    public function setIpAddress(?string $ip_address): self
    {
        $this->ip_address = $ip_address;

        return $this;
    }

    public function getMaxConnections(): ?int
    {
        return $this->max_connections;
    }

    public function setMaxConnections(?int $max_connections): self
    {
        $this->max_connections = $max_connections;

        return $this;
    }

    public function getAllowedUpdates(): array|string|null
    {
        return $this->allowed_updates;
    }

    public function setAllowedUpdates(array|string|null $allowed_updates): self
    {
        $this->allowed_updates = $allowed_updates;

        return $this;
    }

    public function getDropPendingUpdates(): ?bool
    {
        return $this->drop_pending_updates;
    }

    public function setDropPendingUpdates(?bool $drop_pending_updates): self
    {
        $this->drop_pending_updates = $drop_pending_updates;

        return $this;
    }

    public function getSecretToken(): ?string
    {
        return $this->secret_token;
    }

    public function setSecretToken(?string $secret_token): self
    {
        $this->secret_token = $secret_token;

        return $this;
    }

    public function getArray(): array
    {
        return [
            'url' => $this->getUrl()
        ];
    }
}
