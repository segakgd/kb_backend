<?php

namespace App\Dto\SessionCache\Cache;

use App\Dto\Common\AbstractDto;

class CacheContractDto extends AbstractDto
{
    private ?string $message = null;

    private bool $finished = false;

    /** @var array<CacheChainDto> */
    private array $chains = [];

    private ?CacheKeyboardDto $keyboard = null;

    private ?CacheKeyboardDto $attached = null;

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function isFinished(): bool
    {
        return $this->finished;
    }

    public function setFinished(bool $finished): static
    {
        $this->finished = $finished;

        return $this;
    }

    public function getChains(): array
    {
        return $this->chains;
    }

    public function getCurrentChain(): ?CacheChainDto
    {
        if (empty($this->chains)) {
            return null;
        }

        foreach ($this->chains as $chain) {
            if (!$chain->isFinished()) {
                return $chain;
            }
        }

        return null;
    }

    public function hasChain(): bool
    {
        return !empty($this->chains);
    }

    public function setChains(array $chains): static
    {
        $this->chains = $chains;

        return $this;
    }

    public function addChain(CacheChainDto $chain): static
    {
        $this->chains[] = $chain;

        return $this;
    }

    /** @deprecated */
    public function isExistChains(): bool
    {
        return !empty($this->chains);
    }

    public function isEmptyChains(): bool
    {
        return empty($this->chains);
    }

    public function getKeyboard(): ?CacheKeyboardDto
    {
        return $this->keyboard;
    }

    public function setKeyboard(?CacheKeyboardDto $keyboard): static
    {
        $this->keyboard = $keyboard;
        return $this;
    }

    public function getAttached(): ?CacheKeyboardDto
    {
        return $this->attached;
    }

    public function setAttached(?CacheKeyboardDto $attached): static
    {
        $this->attached = $attached;
        return $this;
    }

    public static function fromArray(array $data): static
    {
        $cacheContractDto = new self();
        $cacheContractDto->finished = $data['finished'] ?? false;
        $cacheContractDto->message = $data['message'] ?? null;

        $chains = [];

        // todo костыль, когда мы мапим из dto сценария. Нужно подправить в сценарии этот косяк

        if (isset($data['chains'])) {
            foreach ($data['chains'] ?? [] as $chainData) {
                $chains[] = CacheChainDto::fromArray($chainData);
            }
        }

        if (isset($data['chain'])) {
            foreach ($data['chain'] ?? [] as $chainData) {
                $chains[] = CacheChainDto::fromArray($chainData);
            }
        }

        $cacheContractDto->chains = $chains;

        return $cacheContractDto;
    }

    public function toArray(): array
    {
        $chainsArray = [];
        foreach ($this->chains as $chain) {
            $chainsArray[] = $chain->toArray();
        }

        return [
            'message' => $this->message,
            'finished' => $this->finished,
            'chains' => $chainsArray,
        ];
    }
}
