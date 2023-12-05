<?php

namespace App\Controller\deprecated\Admin\Deal;

use App\Dto\deprecated\Ecommerce\DealDto;
use App\Entity\Lead\Deal;
use App\Entity\User\Project;
use App\Service\Admin\Ecommerce\Deal\DealManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateController extends AbstractController
{
    public function __construct(
        private readonly DealManagerInterface $dealService,
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer
    ) {
    }

//    #[OA\Response(
//        response: 200,
//        description: 'Returns the rewards of an user',
//        content: new Model(
//            properties: [],
//            type: Deal::class,
//            groups: ['administrator']
//        )
//    )]
//    #[OA\Parameter(
//        name: 'order',
//        description: "The field used to order rewards",
//        in: 'query',
//        schema: new OA\Schema(type: 'string')
//    )]
//    #[OA\Parameter(
//        name: 'order',
//        description: "The field used to order rewards",
//        in: 'query',
//        schema: new OA\Schema(type: 'string')
//    )]
//    #[OA\Tag(name: 'Deal')]
//    #[Security(name: 'Bearer')]
//    #[Route('/api/admin/project/{project}/deal/', name: 'admin_deal_create', methods: ['POST'])]
//    #[IsGranted('existUser', 'project')]
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
