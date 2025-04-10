<?php

namespace App\Service\Constructor\Core\Dto;

use App\Doctrine\DoctrineMappingInterface;
use App\Dto\SessionCache\Cache\CacheActionDto;
use App\Dto\SessionCache\Cache\CacheCartDto;
use App\Dto\SessionCache\Cache\CacheEventDto;
use App\Enum\VisitorEventStatusEnum;
use App\Helper\CacheHelper;

class Responsible implements ResponsibleInterface, DoctrineMappingInterface
{
    public ?CacheActionDto $action = null;
    private ?string $content = null;

    private ?CacheCartDto $cart = null;

    private ?CacheEventDto $event = null;

    private ?ResultInterface $result = null;

    private ?VisitorEventStatusEnum $status = VisitorEventStatusEnum::New;

    private ?BotDto $bot = null;

    public function __construct()
    {
        if (is_null($this->result)) {
            $this->result = new Result();
        }

        if (is_null($this->cart)) {
            $this->cart = new CacheCartDto();
        }

        if (is_null($this->event)) {
            $this->event = new CacheEventDto();
        }
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCart(): CacheCartDto
    {
        return $this->cart;
    }

    public function setCart(CacheCartDto $cart): static
    {
        $this->cart = $cart;

        return $this;
    }

    public function getEvent(): ?CacheEventDto
    {
        return $this->event;
    }

    public function setEvent(?CacheEventDto $event): static
    {
        $this->event = $event;

        return $this;
    }

    public function clearEvent(): static
    {
        $this->event = CacheHelper::createCacheEventDto();

        return $this;
    }

    public function getAction(): ?CacheActionDto
    {
        return $this->action;
    }

    public function setAction(?CacheActionDto $action): static
    {
        $this->action = $action;

        return $this;
    }

    public function getResult(): ?ResultInterface
    {
        return $this->result;
    }

    public function setResult(?ResultInterface $result): static
    {
        $this->result = $result;

        return $this;
    }

    public function getStatus(): ?VisitorEventStatusEnum
    {
        return $this->status;
    }

    public function setStatus(?VisitorEventStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getBot(): ?BotDto
    {
        return $this->bot;
    }

    public function setBot(?BotDto $bot): Responsible
    {
        $this->bot = $bot;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'cart'    => $this->cart?->toArray() ?? [],
            'event'   => $this->event?->toArray() ?? [],
            'action'  => $this->action?->toArray() ?? [],
            'result'  => $this->result?->toArray() ?? [],
            'status'  => $this->status->value,
            'bot'     => $this->bot?->toArray() ?? [],
        ];
    }

    public static function fromArray(array $data): static
    {
        $dto = new self();
        $dto->setContent($data['content']);
        $dto->setCart(CacheCartDto::fromArray($data['cart'] ?? []));
        $dto->setEvent(CacheEventDto::fromArray($data['event'] ?? []));
        $dto->setAction(CacheActionDto::fromArray($data['action'] ?? []));
        $dto->setResult(Result::fromArray($data['result'] ?? []));
        $dto->setStatus(VisitorEventStatusEnum::tryFrom($data['status']));
        $dto->setBot(BotDto::fromArray($data['bot'] ?? []));

        return $dto;
    }
}
