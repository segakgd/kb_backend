<?php

namespace App\Command\Local;

use App\Dto\Ecommerce\Product\Variants\VariantPriceDto;
use App\Entity\Ecommerce\ProductVariant;
use App\Service\System\Handler\MessageHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:product-variant-create',
    description: '',
)]
class ProductVariantCreateCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $manager,
        string $name = null,
    ) {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
//        $productVariant = (new ProductVariant())->setName('ddd')->setArticle('eqweqw')
//            ->setPrice(
//                [(new VariantPriceDto())->setPrice(123)->setCurrency('USD')]
//            );
//
//        $this->manager->persist($productVariant);
//        $this->manager->flush();

        $productVariantNew = $this->manager->getRepository(ProductVariant::class)->find(20);

        $f = $productVariantNew->getPrice();
        $f = $f[0];
/** @var $f VariantPriceDto */
        $f->setPrice(333);

        //$newPrice = (new VariantPriceDto())->setPrice(123)->setCurrency('USD');

        $productVariantNew->setPrice([$f]);
//        dd($productVariantNew);

        $this->manager->persist($productVariantNew);
        $this->manager->flush();

        return Command::SUCCESS;
    }
}
