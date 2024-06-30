<?php

namespace App\Command\Single;

use App\Repository\User\UserRepository;
use App\Service\Common\Security\SecurityService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

#[AsCommand(
    name: 'single:user:refresh-token:upload',
    description: 'Upload refresh token for users',
)]
class RefreshUploadCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly SecurityService $securityService,
        ?string $name = null
    ) {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $users = $this->userRepository->findAll();

            foreach ($users as $user) {
                if (is_null($user->getRefreshTokens())) {
                    $this->securityService->resetRefreshToken($user);
                }
            }
        } catch (Throwable $throwable) {
            $io->error($throwable->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
