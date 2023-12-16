<?php

namespace App\Tests\Functional\Cart;

use App\Tests\Functional\ApiTestCase;
use App\Tests\Functional\Trait\CartTrait;
use App\Tests\Functional\Trait\ProductTrait;
use App\Tests\Functional\Trait\Project\ProjectTrait;
use App\Tests\Functional\Trait\User\UserTrait;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class DControllerTest extends ApiTestCase
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
        $client->request(
            'GET',
            '/test/',
        );

        dd($client->getResponse());
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}
