<?php

namespace App\Controller\Admin\Deal;

use App\Dto\Ecommerce\DealDto;
use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\Deal\DealManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;

class CreateController extends AbstractController
{
    public function __construct(
        private DealManagerInterface $dealService,
        private ValidatorInterface $validator,
        private SerializerInterface $serializer
    ) {
    }

    #[OA\Tag(name: 'Deal')]
    #[Route('/api/admin/project/{project}/deal/', name: 'admin_deal_create', methods: ['POST'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project): JsonResponse
    {
        $content = $request->getContent();
        $dealDto = $this->serializer->deserialize($content, DealDto::class, 'json');

        $errors = $this->validator->validate($dealDto);

        if (count($errors) > 0) {
            return $this->json(['message' => $errors->get(0)->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $dealEntity = $this->dealService->add($dealDto, $project->getId());

        return new JsonResponse(
            $this->serializer->normalize(
                $dealEntity,
                null,
                ['groups' => 'administrator']
            )
        );
    }
}
