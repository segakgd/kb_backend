<?php

namespace App\Controller\Admin\Project\DTO\Request;

class TariffSettingReqDto
{
    /** Код применяемого тарифа */
    private ?string $code;

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }
}
