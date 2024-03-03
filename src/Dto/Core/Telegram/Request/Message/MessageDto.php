<?php

namespace App\Dto\Core\Telegram\Request\Message;

class MessageDto
{
    /*
     * Unique identifier for the target chat.
     */
    private int|string $chat_id;

    /*
     * Unique identifier for the target message thread (topic) of the forum; for forum supergroups only
     */
    private ?int $message_thread_id;

    /*
     * Text of the message to be sent, 1-4096 characters after entities parsing
     */
    private string $text;

    /*
     * Mode for parsing entities in the message text.
     */
    private ?string $parse_mode = null;

    /*
     * A JSON-serialized list of special entities that appear in message text, which can be specified instead of parse_mode
     *
     * @var MessageEntity[]|null
     */
    private ?array $entities;

    /*
     * Disables link previews for links in this message
     */
    private ?bool $disable_web_page_preview;

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
    private ?int $reply_to_message_id;

    /*
     * Pass True if the message should be sent even if the specified replied-to message is not found
     */
    private ?bool $allow_sending_without_reply;

    /*
     * Additional interface options. A JSON-serialized object for an inline keyboard, custom reply keyboard,
     * instructions to remove reply keyboard or to force a reply from the user.
     *
     * InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply or null
     */
    private $reply_markup;

    public function getMessageThreadId(): ?int
    {
        return $this->message_thread_id;
    }

    public function setMessageThreadId(?int $message_thread_id): self
    {
        $this->message_thread_id = $message_thread_id;

        return $this;
    }

    public function getEntities(): ?array
    {
        return $this->entities;
    }

    public function setEntities(?array $entities): self
    {
        $this->entities = $entities;

        return $this;
    }

    public function getDisableWebPagePreview(): ?bool
    {
        return $this->disable_web_page_preview;
    }

    public function setDisableWebPagePreview(?bool $disable_web_page_preview): self
    {
        $this->disable_web_page_preview = $disable_web_page_preview;

        return $this;
    }

    public function getDisableNotification(): ?bool
    {
        return $this->disable_notification;
    }

    public function setDisableNotification(?bool $disable_notification): self
    {
        $this->disable_notification = $disable_notification;

        return $this;
    }

    public function getProtectContent(): ?bool
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

    public function getAllowSendingWithoutReply(): ?bool
    {
        return $this->allow_sending_without_reply;
    }

    public function setAllowSendingWithoutReply(?bool $allow_sending_without_reply): self
    {
        $this->allow_sending_without_reply = $allow_sending_without_reply;

        return $this;
    }

    public function getArray(): array
    {
        $normalize = [
            'chat_id' => $this->getChatId(),
            'text' => $this->getText(),
        ];

        if ($this->parse_mode) {
            $normalize['parse_mode'] = $this->getParseMode();
        }

        if (!empty($this->getReplyMarkup())) {
            $normalize['reply_markup'] = json_encode(
                [
                    'keyboard' => $this->getReplyMarkup(),
                ]
            );
        }

        return $normalize;
    }

    public function getChatId(): int|string
    {
        return $this->chat_id;
    }

    public function setChatId(int|string $chat_id): self
    {
        $this->chat_id = $chat_id;

        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getParseMode(): ?string
    {
        return $this->parse_mode;
    }

    public function setParseMode(?string $parse_mode): self
    {
        $this->parse_mode = $parse_mode;

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

    private function keyboardNormalize($keyboards): array
    {
        $result = [];

        foreach ($keyboards as $keyboard) {
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
