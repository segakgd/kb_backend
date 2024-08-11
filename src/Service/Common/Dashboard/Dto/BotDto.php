<?php

namespace App\Service\Common\Dashboard\Dto;

use App\Service\Common\Bot\Enum\BotTypeEnum;

class BotDto
{
    private string $projectName;
    private int $botId;
    private string $botName;
    private int $projectId;
    private BotTypeEnum $botType;
    private ?string $botToken;
    private bool $botActive;
    private ?string $webhookUri;
    private WebhookInfoDto $webhookInfo;

    public function getProjectName(): string
    {
        return $this->projectName;
    }

    public function setProjectName(string $projectName): static
    {
        $this->projectName = $projectName;

        return $this;
    }

    public function getBotId(): int
    {
        return $this->botId;
    }

    public function setBotId(int $botId): static
    {
        $this->botId = $botId;

        return $this;
    }

    public function getBotName(): string
    {
        return $this->botName;
    }

    public function setBotName(string $botName): static
    {
        $this->botName = $botName;

        return $this;
    }

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function setProjectId(int $projectId): static
    {
        $this->projectId = $projectId;

        return $this;
    }

    public function getBotType(): BotTypeEnum
    {
        return $this->botType;
    }

    public function setBotType(BotTypeEnum $botType): static
    {
        $this->botType = $botType;

        return $this;
    }

    public function getBotToken(): ?string
    {
        return $this->botToken;
    }

    public function setBotToken(?string $botToken): static
    {
        $this->botToken = $botToken;

        return $this;
    }

    public function isBotActive(): bool
    {
        return $this->botActive;
    }

    public function setBotActive(bool $botActive): static
    {
        $this->botActive = $botActive;

        return $this;
    }

    public function getWebhookUri(): ?string
    {
        return $this->webhookUri;
    }

    public function setWebhookUri(?string $webhookUri): static
    {
        $this->webhookUri = $webhookUri;

        return $this;
    }

    public function getWebhookInfo(): WebhookInfoDto
    {
        return $this->webhookInfo;
    }

    public function setWebhookInfo(WebhookInfoDto $webhookInfo): static
    {
        $this->webhookInfo = $webhookInfo;

        return $this;
    }
}
