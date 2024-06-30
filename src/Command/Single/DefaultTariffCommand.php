<?php

namespace App\Command\Single;

use App\Entity\User\Tariff;
use App\Repository\User\TariffRepository;
use App\Service\Common\Project\TariffService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

#[AsCommand(
    name: 'single:tariff:default:create',
    description: 'Create default tariff',
)]
class DefaultTariffCommand extends Command
{
    public function __construct(
        private readonly TariffService $tariffService,
        private readonly TariffRepository $tariffRepository,
        ?string $name = null
    ) {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $tariff = $this->tariffService->getTariffByCode(TariffService::DEFAULT_TARIFF_CODE);

            if ($tariff) {
                $io->info('Tariff already created');

                return static::SUCCESS;
            }

            $tariff = (new Tariff())
                ->setName('Триал')
                ->setActive(true)
                ->setCode(TariffService::DEFAULT_TARIFF_CODE)
                ->setPrice(0)
                ->setDescription('Триал период')
                ->setPriceWF(0.0);

            $this->tariffRepository->saveAndFlush($tariff);

            $io->info('Tariff created');
        } catch (Throwable $throwable) {
            $io->error($throwable->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
