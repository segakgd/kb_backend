<?php

namespace App\Controller\deprecated\Admin\Deal;

use App\Dto\deprecated\Ecommerce\DealDto;
use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\Deal\DealManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UpdateController extends AbstractController
{
    public function __construct(
        private readonly DealManagerInterface $dealService,
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer
    ) {
    }

//    #[OA\Tag(name: 'Deal')]
//    #[Route('/api/admin/project/{project}/deal/{dealId}/', name: 'admin_deal_update', methods: ['PUT'])]
//    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project, int $dealId): JsonResponse
    {
        $content = $request->getContent();
        $dealDto = $this->serializer->deserialize($content, DealDto::class, 'json');

//        dd($dealDto);
        $errors = $this->validator->validate($dealDto);

        if (count($errors) > 0) {
            return $this->json(['message' => $errors->get(0)->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $dealEntity = $this->dealService->update($dealDto, $project->getId(), $dealId);

        return new JsonResponse(
            $this->serializer->normalize(
                $dealEntity,
                null,
                ['groups' => 'administrator']
            )
        );
    }
}
