<?php

namespace App\Command\Single;

use App\Dto\Security\UserDto;
use App\Repository\User\UserRepository;
use App\Service\Common\Security\SecurityService;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'dev:create:user-admin',
    description: 'Creates admin user',
)]
class CreateAdminUserCommand extends Command
{
    private const ADMIN_ROLE = 'ROLE_ADMIN';

    private const ADMIN_EMAIL = 'kad@testmail.mail';

    private const ADMIN_PASSWORD = '12345678';

    public function __construct(
        private readonly SecurityService $securityService,
        private readonly UserRepository $userRepository,
        ?string $name = null
    ) {
        parent::__construct($name);
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $user = $this->userRepository->findOneBy(
            [
                'email' => 'kad@testmail.mail',
            ]
        );

        if ($user) {
            $io->info('Admin user already exist');

            return static::SUCCESS;
        }

        $this->securityService->createUser(
            (new UserDto())
                ->setEmail(static::ADMIN_EMAIL)
                ->setPassword(static::ADMIN_PASSWORD),
            static::ADMIN_ROLE
        );

        $io->info('Successfully created admin user');

        return Command::SUCCESS;
    }
}
