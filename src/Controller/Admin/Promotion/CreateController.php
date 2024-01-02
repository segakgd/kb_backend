<?php

namespace App\Controller\Admin\Promotion;

use App\Controller\Admin\Promotion\DTO\Request\PromotionReqDto;
use App\Entity\User\Project;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[OA\Tag(name: 'Promotion')]
#[OA\RequestBody(
    content: new Model(
        type: PromotionReqDto::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: '', // todo You need to write a description
)]
class CreateController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route('/api/admin/project/{project}/promotion/', name: 'admin_promotion_create', methods: ['POST'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project): JsonResponse
    {
        $content = $request->getContent();

        $requestDto = $this->serializer->deserialize($content, PromotionReqDto::class, 'json');

        $errors = $this->validator->validate($requestDto);

        if (count($errors) > 0) {
            return $this->json(['message' => $errors->get(0)->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        // todo ... тут мы должны обратиться к сервису или менеджеру ...

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }
}
