<?php

namespace App\Dto\Admin\Project\Response;

class ProjectMainSettingDto
{
    private ?string $name;

    private ?string $country;

    private ?string $timeZone;

    private ?string $language;

    private ?string $currency;

    private ProjectTariffSettingDto $tariff;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    public function getTimeZone(): ?string
    {
        return $this->timeZone;
    }

    public function setTimeZone(?string $timeZone): void
    {
        $this->timeZone = $timeZone;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): void
    {
        $this->language = $language;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): void
    {
        $this->currency = $currency;
    }

    public function getTariff(): ProjectTariffSettingDto
    {
        return $this->tariff;
    }

    public function setTariff(ProjectTariffSettingDto $tariff): void
    {
        $this->tariff = $tariff;
    }
}
