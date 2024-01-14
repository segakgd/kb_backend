<?php

namespace App\Controller\Dev;

use App\Entity\User\Project;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class CommandStartController extends AbstractController
{
    public function __construct(
        private readonly KernelInterface $kernel
    ) {
    }

    /**
     * @throws \Exception
     */
    #[Route('/dev/project/{project}/command/{command}/start/', name: 'dev_command_start', methods: ['GET'])]
    public function execute(Project $project, string $command): RedirectResponse
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(
            [
                'command' => $command,
            ]
        );

        $application->run($input);

        return new RedirectResponse('/');
    }
}
