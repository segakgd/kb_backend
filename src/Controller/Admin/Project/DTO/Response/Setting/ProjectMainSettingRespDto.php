<?php

namespace App\Controller\Admin\Project\DTO\Response\Setting;

use App\Controller\Admin\Project\DTO\Response\ProjectTariffSettingRespDto;

class ProjectMainSettingRespDto
{
    private ?string $name;

    private ?string $country;

    private ?string $timeZone;

    private ?string $language;

    private ?string $currency;

    private ProjectTariffSettingRespDto $tariff;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getTimeZone(): ?string
    {
        return $this->timeZone;
    }

    public function setTimeZone(?string $timeZone): self
    {
        $this->timeZone = $timeZone;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getTariff(): ProjectTariffSettingRespDto
    {
        return $this->tariff;
    }

    public function setTariff(ProjectTariffSettingRespDto $tariff): self
    {
        $this->tariff = $tariff;

        return $this;
    }
}
