<?php

namespace App\Service\Constructor\Scheme\Items;

use App\Dto\Scenario\ScenarioChainDto;
use App\Dto\Scenario\ScenarioContractDto;
use App\Dto\Scenario\ScenarioDto;
use App\Enum\TargetEnum;
use App\Service\Constructor\Scheme\SchemeInterface;

class ProductByCategoryChainSchemeInterface implements SchemeInterface
{
    public static function scheme(): ScenarioDto
    {
        $uuid = uuid_create();

        return (new ScenarioDto())
            ->setUUID($uuid)
            ->setName('Товары по категориям')
            ->setType('message')
            ->setContract(
                (new ScenarioContractDto())
                    ->setMessage(null)
                    ->setKeyboard(null)
                    ->setAttached(null)
                    ->addChain(
                        (new ScenarioChainDto())->setTarget(TargetEnum::StartChain->value)
                    )
                    ->addChain(
                        (new ScenarioChainDto())->setTarget(TargetEnum::ProductCategoryChain->value)
                    )
                    ->addChain(
                        (new ScenarioChainDto())->setTarget(TargetEnum::ProductsByCategoryChain->value)
                    )
                    ->addChain(
                        (new ScenarioChainDto())->setTarget(TargetEnum::VariantsProductChain->value)
                    )
                    ->addChain(
                        (new ScenarioChainDto())->setTarget(TargetEnum::VariantProductChain->value)
                    )
                    ->addChain(
                        (new ScenarioChainDto())->setTarget(TargetEnum::FinishChain->value)
                    )
            );
    }
}
