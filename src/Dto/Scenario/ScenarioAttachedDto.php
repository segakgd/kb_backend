<?php

namespace App\Dto\Scenario;

class ScenarioAttachedDto
{
    private ?string $document = null;

    private ?string $link = null;

    private ?array $images = null;

    private ?array $videos = null;

    private ?array $audios = null;

    public function getDocument(): ?string
    {
        return $this->document;
    }

    public function setDocument(?string $document): static
    {
        $this->document = $document;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function getImages(): ?array
    {
        return $this->images;
    }

    public function addImages(array $image): static
    {
        $this->images[] = $image;

        return $this;
    }

    public function getVideos(): ?array
    {
        return $this->videos;
    }

    public function addVideos(array $video): static
    {
        $this->videos[] = $video;

        return $this;
    }

    public function getAudios(): ?array
    {
        return $this->audios;
    }

    public function addAudios(array $audio): static
    {
        $this->audios[] = $audio;

        return $this;
    }

    public static function fromArray(array $data): self
    {
        $attached = new self();
        $attached->setDocument($data['document'] ?? null);
        $attached->setLink($data['link'] ?? null);
        $attached->addImages($data['images'] ?? null);
        $attached->addVideos($data['videos'] ?? null);
        $attached->addAudios($data['audios'] ?? null);

        return $attached;
    }

    public function toArray(): array
    {
        return [
            'document' => $this->getDocument(),
            'link' => $this->getLink(),
            'images' => $this->getImages(),
            'videos' => $this->getVideos(),
            'audios' => $this->getAudios(),
        ];
    }
}
