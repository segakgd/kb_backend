<?php

namespace App\Controller\Admin\Project\Request;

use App\Service\Common\Project\Enum\TariffCodeEnum;

class TariffSettingRequest
{
    /** Код применяемого тарифа */
    private ?TariffCodeEnum $code;

    public function getCode(): ?TariffCodeEnum
    {
        return $this->code;
    }

    public function setCode(?TariffCodeEnum $code): static
    {
        $this->code = $code;

        return $this;
    }
}
