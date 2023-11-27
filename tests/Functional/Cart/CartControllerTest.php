<?php

namespace App\Tests\Functional\Cart;

use App\Dto\Ecommerce\PriceDto;
use App\Service\Mapper\Ecommerce\PriceMapper;
use App\Service\Mapper\Ecommerce\ProductMapper;
use App\Tests\Functional\ApiTestCase;
use App\Tests\Functional\Trait\CartTrait;
use App\Tests\Functional\Trait\ProductTrait;
use App\Tests\Functional\Trait\Project\ProjectTrait;
use App\Tests\Functional\Trait\User\UserTrait;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class CartControllerTest extends ApiTestCase
{
    use UserTrait;
    use ProjectTrait;
    use CartTrait;
    use ProductTrait;

    /**
     * @throws Exception
     */
    public function testCart()
    {
        $client = static::createClient();
        $entityManager = $this->getEntityManager();

        $user = $this->createUser($entityManager);
        $project = $this->createProject($entityManager, $user);

        $price1 = (new PriceDto())->setValue(10000)->setValueFraction('100.00');
        $price1 = PriceMapper::toArrFromDto($price1);
        $product1 = $this->createProduct($entityManager, $project, $price1);
        $product1 = ProductMapper::mapToArray($product1);

        $price2 = (new PriceDto())->setValue(15000)->setValueFraction('150.00');
        $price2 = PriceMapper::toArrFromDto($price2);
        $product2 = $this->createProduct($entityManager, $project, $price2);
        $product2 = ProductMapper::mapToArray($product2);

        $cart = $this->createCart($entityManager, [$product1, $product2], 1, 25000);

        $client->request(
            'POST',
            '/visitor/project/' . $project->getId() .'/cart/',
            [],
            [],
            [],
            json_encode(
                [
                    'products' => $cart->getProducts(),
                    'totalAmount' => $cart->getTotalAmount(),
                    'visitorId' => $cart->getVisitorId(),
                    'status' => $cart->getStatus(),
                ]
            )
        );

        dd($client->getResponse());
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}
