<?php

namespace App\Tests\Functional\Admin\Lead;

use App\Tests\Functional\ApiTestCase;
use App\Tests\Functional\Trait\Project\ProjectTrait;
use App\Tests\Functional\Trait\User\UserTrait;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ViewOneControllerTest extends ApiTestCase
{
    use UserTrait;
    use ProjectTrait;

    /**
     * @dataProvider positive
     *
     * @throws Exception
     */
    public function test(array $response)
    {
        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);
        $project = $this->createProject($entityManager, $user);

        $entityManager->flush();

        $client->loginUser($user);

        $client->request(
            'GET',
            '/api/admin/project/'. $project->getId() .'/lead/' . 1 . '/', // todo ВНИМАНИЕ! я пока что поставил 1, но нужно брать существующий продукт
        );

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertResponse($client->getResponse()->getContent(), $response);
    }


    private function positive(): iterable
    {
        yield [
            [
                "number" => 111,
                "contacts" => [
                    "fullName" => null,
                    "phone" => null,
                    "mail" => [
                        "type" => "mail",
                        "name" => "Почта",
                        "value" => "fake@mail.fake"
                    ],
                    "telegram" => null
                ],
                "status" => "new",
                "order" => [
                    "products" => [
                        [
                            "name" => "Product name",
                            "type" => "service",
                            "category" => [
                                "name" => "Category name"
                            ],
                            "variant" => [
                                "name" => "Variant name",
                                "count" => 2,
                                "price" => 50000
                            ],
                            "image" => "image.fake",
                            "totalCount" => 2,
                            "totalAmount" => 10000,
                            "totalAmountWF" => "100,00"
                        ]
                    ],
                    "shipping" => [
                        "name" => "Shipping name",
                        "type" => "courier",
                        "totalAmount" => 10000,
                        "totalAmountWF" => "100,00"
                    ],
                    "promotions" => [
                        [
                            "name" => "Promotion Name",
                            "calculationType" => "percent",
                            "code" => "PROMO_2024",
                            "discount" => 30,
                            "totalAmount" => 10000,
                            "totalAmountWF" => "100,00"
                        ]
                    ],
                    "payment" => [
                        "paymentStatus" => true,
                        "productPrice" => 10000,
                        "shippingPrice" => 10000,
                        "promotionSum" => 10000,
                        "totalAmount" => 30000,
                        "totalAmountWF" => "300,00"
                    ],
                    "createdAt" => "2023-12-17T23:16:59+00:00"
                ],
                "chanel" => [
                    "name" => "telegram",
                    "link" => "link"
                ],
                "createdAt" => "2023-12-17T23:16:59+00:00"
            ]
        ];
    }
}
