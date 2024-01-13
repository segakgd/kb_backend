<?php

namespace App\Controller\Admin\History;

use App\Controller\Admin\History\DTO\Request\HistoryReqDto;
use App\Controller\Admin\History\DTO\Response\Errors\HistoryErrorDto;
use App\Controller\Admin\History\DTO\Response\HistoryErrorRespDto;
use App\Controller\Admin\History\DTO\Response\HistoryRespDto;
use App\Entity\History\History;
use App\Entity\User\Project;
use App\Exception\History\HistoryException;
use App\Service\Admin\History\HistoryServiceInterface;
use DateTimeImmutable;
use Exception;
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
use function PHPUnit\Framework\throwException;

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
        private readonly SerializerInterface $serializer,
        private readonly HistoryServiceInterface $historyService,
    ) {
    }

    #[Route('/api/admin/project/{project}/history/', name: 'admin_history_get_all', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Request $request, Project $project): JsonResponse
    {
        $content = $request->getContent();

        $requestDto = $this->serializer->deserialize($content, HistoryReqDto::class, 'json'); // todo  гет запрос не должно быть json. Нужно все перевести в строку

        $errors = $this->validator->validate($requestDto);

        if (count($errors) > 0) {
            return $this->json(['message' => $errors->get(0)->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $history = $this->historyService->findAll($project->getId());

        throw new HistoryException(
            $project->getId(),
            'message',
            'new',
            'vk',
            'bitrix',
            new HistoryErrorRespDto()
        );

        throw new HistoryException(
            $project->getId(),
            'message',
            'process',
            'telegram',
            'flexbe',
            new HistoryErrorRespDto()
        );

        throw new HistoryException(
            $project->getId(),
            'message',
            'success',
            'vk',
            'amoCRM',
            new HistoryErrorRespDto()
        );

        throw new HistoryException(
            $project->getId(),
            'message',
            'succes',
            'telegram',
            'bitrix',
            new HistoryErrorRespDto()
        );

        $response = $this->mapToResponse($history);

        return new JsonResponse(
            $this->serializer->normalize($response)
        );
    }

    private function mapToResponse(array $histories): array
    {
        $result = [];

        /** @var History $history */
        foreach ($histories as $history){

            $error = null;

            if(null !== $history->getError())
            {
                $fakeErrorContext = (new HistoryErrorDto())
                    ->setMessage($actualError['message'] ?? 'default message') // todo нужно сюда придумать что за дефолтный код
                ;

                $actualError = $history->getError();

                $error = (new HistoryErrorRespDto())
                    ->setCode($actualError['code'] ?? 'default code') // todo нужно сюда придумать что за дефолтный код
                    ->addContext($fakeErrorContext)
                ;
            }

            $result[] = (new HistoryRespDto())
                ->setType($history->getType())
                ->setStatus($history->getStatus())
                ->setSender($history->getSender())
                ->setRecipient($history->getRecipient())
                ->setError($error)
                ->setCreatedAt($history->getCreatedAt())
            ;
        }

        return $result;
    }
}
