<?php

namespace App\Command\Dev;

use App\Dto\Ecommerce\Product\Variants\VariantPriceDto;
use App\Entity\Ecommerce\ProductVariant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'dev:create:product-variant',
    description: 'Convert user setting',
)]
class CreateProductCreateCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $variant = $this->get();

        /** @var VariantPriceDto $price */
        $price = $variant->getPrice();

        $onePrice = $price[0];

        $onePrice->setCost(2322);

        $variant->setPrice([clone $onePrice]);
//        $variant->addPrice($onePrice);

        $this->update($variant);

        return Command::SUCCESS;
    }

    private function update(ProductVariant $productVariant): void
    {
        $this->entityManager->persist($productVariant);
        $this->entityManager->flush();
    }

    protected function get(): ProductVariant
    {
        $repository = $this->entityManager->getRepository(ProductVariant::class);

        return $repository->find(1);
    }

    private function createAndSave(): ProductVariant
    {
        $variantPriceDto = (new VariantPriceDto)
            ->setCurrency('RUB')
            ->setCost(100);

        $productVariant = (new ProductVariant())
            ->setName('Название продукта')
            ->setArticle(uuid_create())
            ->setPrice([$variantPriceDto]);

        $this->entityManager->persist($productVariant);
        $this->entityManager->flush();

        return $productVariant;
    }
}
