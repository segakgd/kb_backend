<?php

namespace App\Controller\Admin\Project;

use App\Controller\Admin\Project\DTO\Request\ProjectCreateReqDto;
use App\Controller\Admin\Project\DTO\Response\ProjectRespDto;
use App\Entity\User\Project;
use App\Repository\User\UserRepository;
use App\Service\Admin\Statistic\StatisticsServiceInterface;
use App\Service\Common\Project\ProjectServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[OA\Tag(name: 'Project')]
#[OA\RequestBody(
    content: new Model(
        type: ProjectCreateReqDto::class,
    )
)]
#[OA\Response(
    response: Response::HTTP_NO_CONTENT,
    description: 'Возвращает 204 при создании новго проекта',
)]
class CreateController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
        private readonly ProjectServiceInterface $projectService,
        private readonly UserRepository $userRepository,
        private readonly StatisticsServiceInterface $statisticsService,
    ) {
    }

    #[Route('/api/admin/project/', name: 'admin_project_create', methods: ['POST'])]
    public function execute(Request $request): JsonResponse
    {
        if (null === $this->getUser()){
            return new JsonResponse([], Response::HTTP_FORBIDDEN);
        }

        $content = $request->getContent();

        $requestDto = $this->serializer->deserialize($content, ProjectCreateReqDto::class, 'json');

        $errors = $this->validator->validate($requestDto);

        if (count($errors) > 0) {
            return $this->json(['message' => $errors->get(0)->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->userRepository->find($this->getUser());

        if (null === $user){
            return new JsonResponse([], Response::HTTP_FORBIDDEN);
        }

        $project = $this->projectService->add($requestDto, $user);
        $response = $this->mapToResponse($project);

        return new JsonResponse(
            $this->serializer->normalize($response)
        );
    }

    private function mapToResponse(Project $project): ProjectRespDto
    {
        $fakeStatisticsByProject = $this->statisticsService->getStatisticForProject();

        return (new ProjectRespDto())
            ->setId($project->getId())
            ->setName($project->getName())
            ->setStatus($project->getStatus())
            ->setStatistic($fakeStatisticsByProject)
            ->setActiveFrom($project->getActiveFrom())
            ->setActiveTo($project->getActiveTo())
            ;
    }
}
