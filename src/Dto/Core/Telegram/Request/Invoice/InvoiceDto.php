<?php

namespace App\Dto\Core\Telegram\Request\Invoice;

class InvoiceDto
{
private int|string $chat_id;
private int $message_thread_id;
private string $title;
private string $description;
private string $payload;
private string $provider_token;
private string $currency;
private array|string $prices;
private ?int $max_tip_amount;
private ?array $suggested_tip_amounts;
private ?string $start_parameter;
private ?string $provider_data;
private ?string $photo_url;
private ?int $photo_size;
private ?int $photo_width;
private ?int $photo_height;
private ?bool $need_name;
private ?bool $need_phone_number;
private ?bool $need_email;
private ?bool $need_shipping_address;
private ?bool $send_phone_number_to_provider;
private ?bool $send_email_to_provider;
private ?bool $is_flexible;
private ?bool $disable_notification;
private ?bool $protect_content;
private ?int $reply_to_message_id;
private ?bool $allow_sending_without_reply;
private $reply_markup;

    public function getChatId(): int|string
    {
        return $this->chat_id;
    }

    public function setChatId(int|string $chat_id): self
    {
        $this->chat_id = $chat_id;

        return $this;
    }

    public function getMessageThreadId(): int
    {
        return $this->message_thread_id;
    }

    public function setMessageThreadId(int $message_thread_id): self
    {
        $this->message_thread_id = $message_thread_id;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPayload(): string
    {
        return $this->payload;
    }

    public function setPayload(string $payload): self
    {
        $this->payload = $payload;

        return $this;
    }

    public function getProviderToken(): string
    {
        return $this->provider_token;
    }

    public function setProviderToken(string $provider_token): self
    {
        $this->provider_token = $provider_token;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getPrices(): array|string
    {
        return $this->prices;
    }

    public function setPrices(array|string $prices): self
    {
        $this->prices = $prices;

        return $this;
    }

    public function getMaxTipAmount(): ?int
    {
        return $this->max_tip_amount;
    }

    public function setMaxTipAmount(?int $max_tip_amount): self
    {
        $this->max_tip_amount = $max_tip_amount;

        return $this;
    }

    public function getSuggestedTipAmounts(): ?array
    {
        return $this->suggested_tip_amounts;
    }

    public function setSuggestedTipAmounts(?array $suggested_tip_amounts): self
    {
        $this->suggested_tip_amounts = $suggested_tip_amounts;

        return $this;
    }

    public function getStartParameter(): ?string
    {
        return $this->start_parameter;
    }

    public function setStartParameter(?string $start_parameter): self
    {
        $this->start_parameter = $start_parameter;

        return $this;
    }

    public function getProviderData(): ?string
    {
        return $this->provider_data;
    }

    public function setProviderData(?string $provider_data): self
    {
        $this->provider_data = $provider_data;

        return $this;
    }

    public function getPhotoUrl(): ?string
    {
        return $this->photo_url;
    }

    public function setPhotoUrl(?string $photo_url): self
    {
        $this->photo_url = $photo_url;

        return $this;
    }

    public function getPhotoSize(): ?int
    {
        return $this->photo_size;
    }

    public function setPhotoSize(?int $photo_size): self
    {
        $this->photo_size = $photo_size;

        return $this;
    }

    public function getPhotoWidth(): ?int
    {
        return $this->photo_width;
    }

    public function setPhotoWidth(?int $photo_width): self
    {
        $this->photo_width = $photo_width;

        return $this;
    }

    public function getPhotoHeight(): ?int
    {
        return $this->photo_height;
    }

    public function setPhotoHeight(?int $photo_height): self
    {
        $this->photo_height = $photo_height;

        return $this;
    }

    public function isNeedName(): ?bool
    {
        return $this->need_name;
    }

    public function setNeedName(?bool $need_name): self
    {
        $this->need_name = $need_name;

        return $this;
    }

    public function isNeedPhoneNumber(): ?bool
    {
        return $this->need_phone_number;
    }

    public function setNeedPhoneNumber(?bool $need_phone_number): self
    {
        $this->need_phone_number = $need_phone_number;

        return $this;
    }

    public function isNeedEmail(): ?bool
    {
        return $this->need_email;
    }

    public function setNeedEmail(?bool $need_email): self
    {
        $this->need_email = $need_email;

        return $this;
    }

    public function isNeedShippingAddress(): ?bool
    {
        return $this->need_shipping_address;
    }

    public function setNeedShippingAddress(?bool $need_shipping_address): self
    {
        $this->need_shipping_address = $need_shipping_address;

        return $this;
    }

    public function isSendPhoneNumberToProvider(): ?bool
    {
        return $this->send_phone_number_to_provider;
    }

    public function setSendPhoneNumberToProvider(?bool $send_phone_number_to_provider): self
    {
        $this->send_phone_number_to_provider = $send_phone_number_to_provider;

        return $this;
    }

    public function isSendEmailToProvider(): ?bool
    {
        return $this->send_email_to_provider;
    }

    public function setSendEmailToProvider(?bool $send_email_to_provider): self
    {
        $this->send_email_to_provider = $send_email_to_provider;

        return $this;
    }

    public function isIsFlexible(): ?bool
    {
        return $this->is_flexible;
    }

    public function setIsFlexible(?bool $is_flexible): self
    {
        $this->is_flexible = $is_flexible;

        return $this;
    }

    public function isDisableNotification(): ?bool
    {
        return $this->disable_notification;
    }

    public function setDisableNotification(?bool $disable_notification): self
    {
        $this->disable_notification = $disable_notification;

        return $this;
    }

    public function isProtectContent(): ?bool
    {
        return $this->protect_content;
    }

    public function setProtectContent(?bool $protect_content): self
    {
        $this->protect_content = $protect_content;

        return $this;
    }

    public function getReplyToMessageId(): ?int
    {
        return $this->reply_to_message_id;
    }

    public function setReplyToMessageId(?int $reply_to_message_id): self
    {
        $this->reply_to_message_id = $reply_to_message_id;

        return $this;
    }

    public function isAllowSendingWithoutReply(): ?bool
    {
        return $this->allow_sending_without_reply;
    }

    public function setAllowSendingWithoutReply(?bool $allow_sending_without_reply): self
    {
        $this->allow_sending_without_reply = $allow_sending_without_reply;

        return $this;
    }

    public function getReplyMarkup()
    {
        return $this->reply_markup;
    }

    public function setReplyMarkup($reply_markup): self
    {
        $this->reply_markup = $reply_markup;

        return $this;
    }

    public function getArray(): array
    {
        $normalize = [
            'chat_id' => $this->getChatId(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'payload' => $this->getPayload(),
            'photo_url' => $this->getPhotoUrl(),
            'provider_token' => $this->getProviderToken(), // 381764678:TEST:60367
            'currency' => $this->getCurrency(), // RUB
            'prices' => $this->getPrices(), // [{"label":"first","amount":"200"}]
            'photo_uri' => $this->getPhotoUrl() ?? '', // [{"label":"first","amount":"200"}]
        ];

        if (!empty($this->getReplyMarkup())){
            $normalize['reply_markup'] = json_encode([
                'inline_keyboard' => $this->getReplyMarkup(),
            ]);
        }

        return $normalize;
    }
}
