<?php

namespace App\Tests\Integration;

use App\Dto\SessionCache\Cache\CacheCartDto;
use App\Dto\SessionCache\Cache\CacheDataDto;
use App\Dto\SessionCache\Cache\CacheEventDto;
use App\Service\Constructor\Actions\Ecommerce\ProductsByCategoryAction;
use App\Service\Constructor\Core\Dto\Responsible;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 * @coversNothing
 */
class ProductsByCategoryChainTest extends KernelTestCase
{
    /**
     * @throws Exception
     */
    public function testSuccess(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        $responsible = new Responsible();

        $responsible
            ->setCart(
                new CacheCartDto()
            )
            ->setContent(
                'Предыдущий'
            )
            ->setEvent(
                (new CacheEventDto())
                    ->setData(
                        (new CacheDataDto())
                            ->setCategoryId(2)
                            ->setPageNow(1)
                    )
            );

        /** @var ProductsByCategoryAction $chainService */
        $chainService = $container->get(ProductsByCategoryAction::class);
        $chainService->before($responsible);

        $this->assertNotEmpty($responsible->getResult()->getMessage());
    }
}
