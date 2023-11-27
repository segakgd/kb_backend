<?php

namespace App\Controller;

use App\Entity\Lead\DealField;
use App\Service\Reflection\MirrorEntityManager;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SandboxController extends AbstractController
{
    public function __construct(
        private MirrorEntityManager $mirrorEntityManager
    ) {
    }

    /**
     * @throws ReflectionException
     */
    #[Route('/sandbox/mirror/', name: 'mirror', methods: ['GET'])]
    public function execute(): JsonResponse
    {
        $reflection = $this->mirrorEntityManager->find(DealField::class, 1);

        $reflection->map('{"name": "ываывsssssssа"}');

        $this->mirrorEntityManager->save(DealField::class, 1, $reflection);

        return new JsonResponse([$reflection, $reflection->getId()]);
    }
}
