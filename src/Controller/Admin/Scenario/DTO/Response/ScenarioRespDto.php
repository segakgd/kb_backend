<?php

namespace App\Controller\Admin\Scenario\DTO\Response;

use App\Controller\Admin\ProductCategory\DTO\Response\ProductCategoryRespDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

class ScenarioRespDto
{
    private readonly ?int $id;

    private readonly string $name;

    // todo реализовать
//    #[OA\Property(
//        type: "array",
//        items: new OA\Items(
//            ref: new Model(
//                type: ProductCategoryRespDto::class
//            )
//        )
//    )]
//    private readonly array $scenario;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

//    public function getScenario(): array
//    {
//        return $this->scenario;
//    }
//
//    public function setScenario(array $scenario): void
//    {
//        $this->scenario = $scenario;
//    }
}
