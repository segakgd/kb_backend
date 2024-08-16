<?php

namespace App\Service\Common\Project;

use App\Controller\Admin\Project\Request\ProjectSettingRequest;
use App\Entity\User\ProjectSetting;
use App\Entity\User\Tariff;

interface ProjectSettingServiceInterface
{

    public function getSettingForProject(int $projectId): ProjectSetting;

    public function updateSetting(int $projectId, ProjectSettingRequest $projectSettingReq): ProjectSetting;

    public function updateTariff(int $projectId, Tariff $tariff): bool;

    public function initSetting(int $projectId): void;
}
