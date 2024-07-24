<?php

namespace App\Tests\Integration;

use App\Dto\SessionCache\Cache\CacheCartDto;
use App\Dto\SessionCache\Cache\CacheDataDto;
use App\Dto\SessionCache\Cache\CacheDto;
use App\Dto\SessionCache\Cache\CacheEventDto;
use App\Service\Constructor\Actions\Ecommerce\ProductsByCategoryChain;
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
            ->setCacheDto(
                (new CacheDto())
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
                    )
            );

        /** @var ProductsByCategoryChain $chainService */
        $chainService = $container->get(ProductsByCategoryChain::class);
        $chainService->perform($responsible);

        $this->assertNotEmpty($responsible->getResult()->getMessage());
    }
}
