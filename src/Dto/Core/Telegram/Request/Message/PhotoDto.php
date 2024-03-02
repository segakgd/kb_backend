<?php

namespace App\Dto\Core\Telegram\Request\Message;

class PhotoDto
{
    /*
     * Unique identifier for the target chat.
     */
    private int|string $chat_id;

    /*
     * Unique identifier for the target message thread (topic) of the forum; for forum supergroups only
     */
    private ?int $message_thread_id;

    private string $photo;

    /*
     * Text of the message to be sent, 1-4096 characters after entities parsing
     */
    private string $caption;

    /*
     * Mode for parsing entities in the message text.
     */
    private ?string $parse_mode;

    /*
     * A JSON-serialized list of special entities that appear in message text, which can be specified instead of parse_mode
     *
     * @var MessageEntity[]|null
     */
    private ?array $caption_entities;

    /*
     * Disables link previews for links in this message
     */
    private ?bool $has_spoiler;

    /*
     * Sends the message silently. Users will receive a notification with no sound.
     */
    private ?bool $disable_notification;

    /*
     * Protects the contents of the sent message from forwarding and saving
     */
    private ?bool $protect_content;

    /*
     * If the message is a reply, ID of the original message
     */
    private ?array $reply_parameters;

    /*
     * Additional interface options. A JSON-serialized object for an inline keyboard, custom reply keyboard,
     * instructions to remove reply keyboard or to force a reply from the user.
     *
     * InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply or null
     */
    private $reply_markup;

    public function getChatId(): int|string
    {
        return $this->chat_id;
    }

    public function setChatId(int|string $chat_id): static
    {
        $this->chat_id = $chat_id;

        return $this;
    }

    public function getMessageThreadId(): ?int
    {
        return $this->message_thread_id;
    }

    public function setMessageThreadId(?int $message_thread_id): static
    {
        $this->message_thread_id = $message_thread_id;

        return $this;
    }

    public function getPhoto(): string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getCaption(): string
    {
        return $this->caption;
    }

    public function setCaption(string $caption): static
    {
        $this->caption = $caption;

        return $this;
    }

    public function getParseMode(): ?string
    {
        return $this->parse_mode;
    }

    public function setParseMode(?string $parse_mode): static
    {
        $this->parse_mode = $parse_mode;

        return $this;
    }

    public function getCaptionEntities(): ?array
    {
        return $this->caption_entities;
    }

    public function setCaptionEntities(?array $caption_entities): static
    {
        $this->caption_entities = $caption_entities;

        return $this;
    }

    public function getHasSpoiler(): ?bool
    {
        return $this->has_spoiler;
    }

    public function setHasSpoiler(?bool $has_spoiler): static
    {
        $this->has_spoiler = $has_spoiler;

        return $this;
    }

    public function getDisableNotification(): ?bool
    {
        return $this->disable_notification;
    }

    public function setDisableNotification(?bool $disable_notification): static
    {
        $this->disable_notification = $disable_notification;

        return $this;
    }

    public function getProtectContent(): ?bool
    {
        return $this->protect_content;
    }

    public function setProtectContent(?bool $protect_content): static
    {
        $this->protect_content = $protect_content;

        return $this;
    }

    public function getReplyParameters(): ?array
    {
        return $this->reply_parameters;
    }

    public function setReplyParameters(?array $reply_parameters): static
    {
        $this->reply_parameters = $reply_parameters;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getReplyMarkup()
    {
        return $this->reply_markup;
    }

    /**
     * @param mixed $reply_markup
     */
    public function setReplyMarkup($reply_markup): static
    {
        $this->reply_markup = $reply_markup;

        return $this;
    }

    public function getArray(): array
    {
        $normalize = [
            'chat_id' => $this->getChatId(),
            'photo' => $this->getPhoto(),
            'caption' => $this->getCaption(),
            'parse_mode' => $this->getParseMode(),
        ];

        if (!empty($this->getReplyMarkup())){
            $normalize['reply_markup'] = json_encode(
                [
                    'keyboard' => $this->getReplyMarkup(),
                ]
            );
        }

        return $normalize;
    }

    private function keyboardNormalize($keyboards): array
    {
        $result = [];

        foreach ($keyboards as $keyboard){
            $result[] = [
                [
                    'text' => $keyboard['text']
                ],
                [
                    'text' => $keyboard['text']
                ],
            ];
        }

        return $result;
    }
}
