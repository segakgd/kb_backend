<?php

namespace App\Controller\Admin\History;

use App\Controller\Admin\History\DTO\Request\HistoryReqDto;
use App\Controller\Admin\History\DTO\Response\Errors\HistoryErrorDto;
use App\Controller\Admin\History\DTO\Response\HistoryErrorRespDto;
use App\Controller\Admin\History\DTO\Response\HistoryRespDto;
use App\Entity\User\Project;
use DateTimeImmutable;
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

#[OA\Tag(name: 'History')]
#[OA\RequestBody(
    content: new Model(
        type: HistoryReqDto::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Выводим историю проекта (пользовательские логи)',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(
            ref: new Model(
                type: HistoryRespDto::class
            )
        )
    ),
)]
class ViewAllController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer
    ) {
    }

    #[Route('/api/admin/project/{project}/history/', name: 'admin_history_get_all', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project): JsonResponse
    {
        $content = $request->getContent();

        $requestDto = $this->serializer->deserialize($content, HistoryReqDto::class, 'json');

        $errors = $this->validator->validate($requestDto);

        if (count($errors) > 0) {
            return $this->json(['message' => $errors->get(0)->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        // todo ... тут мы должны обратиться к сервису или менеджеру ...

        $fakeErrorContext = new HistoryErrorDto();

        $fakeError = (new HistoryErrorRespDto())
            ->setCode('FAKE_CODE')
            ->addContext($fakeErrorContext)
        ;

        $fakeHistory = (new HistoryRespDto())
            ->setType(HistoryRespDto::HISTORY_TYPE_MESSAGE_SENDING)
            ->setStatus(HistoryRespDto::HISTORY_STATUS_ERROR)
            ->setSender(HistoryRespDto::SENDER_VK)
            ->setRecipient('@user_name')
            ->setError($fakeError)
            ->setCreatedAt(new DateTimeImmutable())
        ;

        return new JsonResponse(
            $this->serializer->normalize(
                [
                    $fakeHistory,
                    $fakeHistory,
                ]
            )
        );
    }
}
