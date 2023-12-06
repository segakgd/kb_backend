<?php

namespace App\Dto\Admin\Project\Request;

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
