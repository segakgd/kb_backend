<?php

declare(strict_types=1);

namespace App\Controller\Admin\Lead;

use App\Controller\Admin\Lead\DTO\Response\Fields\LeadContactsRespDto;
use App\Controller\Admin\Lead\DTO\Response\Fields\LeadFieldRespDto;
use App\Controller\Admin\Lead\DTO\Response\LeadRespDto;
use App\Controller\Admin\Lead\DTO\Response\Order\OrderRespDto;
use App\Entity\Lead\Deal;
use App\Entity\User\Project;
use App\Repository\Lead\ContactsEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[OA\Tag(name: 'Lead')]
#[OA\Response(
    response: Response::HTTP_OK,
    description: 'Возвращает заявку',
    content: new Model(
        type: LeadRespDto::class
    ),
)]
class ViewOneController extends AbstractController
{
    public function __construct(
        private readonly ContactsEntityRepository $contactsEntityRepository,
        private readonly EntityManagerInterface  $manager,
    )
    {
    }

    #[Route('/api/admin/project/{project}/lead/{lead}/', name: 'admin_lead_get_one', methods: ['GET'])]
    #[IsGranted('existUser', 'project')]
    public function execute(Project $project, ?Deal $lead): JsonResponse
    {
        if (null === $lead) {
            return $this->json(['Lead not found'], Response::HTTP_NOT_FOUND);
        }

        if ($project->getId() !== $lead->getProjectId()) {
            throw new AccessDeniedException('Access Denied.');
        }

        return $this->json($this->mapToResponse($lead));
    }

    private function mapToResponse(Deal $deal): LeadRespDto // todo точно в сервис перекинуть эту кашу!
    {
        $leadContactsRespDto = $this->mapContactsToResponse($deal);
        $fieldsRespArray = $this->mapFieldsToResponse($deal);
        $orderDto = $this->mapOrderToResponse($deal);

        return (new LeadRespDto())
            ->setContacts($leadContactsRespDto)
            ->setFields($fieldsRespArray)
            ->setNumber($deal->getId())
            ->setOrder($orderDto);
    }

    private function mapContactsToResponse(Deal $deal): LeadContactsRespDto
    {
        $leadContactsRespDto = new LeadContactsRespDto();
        $contacts = $deal->getContacts();

        if (null === $contacts) {
            return $leadContactsRespDto;
        }

        $this->manager->refresh($contacts); // temporary fix

        if ($contacts->getEmail()) {
            $emailField = (new LeadFieldRespDto())
                ->setName('email')
                ->setType('email')
                ->setValue($contacts->getEmail());

            $leadContactsRespDto->setMail($emailField);
        }

        if ($contacts->getPhone()) {
            $phoneField = (new LeadFieldRespDto())
                ->setName('phone')
                ->setType('phone')
                ->setValue($contacts->getPhone());

            $leadContactsRespDto->setPhone($phoneField);
        }

        if ($contacts->getLastName() || $contacts->getFirstName()) {
            $fullName = ($contacts->getFirstName() ?? '') . ' ' . ($contacts->getLastName() ?? '');

            $fullNameField = (new LeadFieldRespDto)
                ->setName('fullName')
                ->setType('full_name')
                ->setValue($fullName);

            $leadContactsRespDto->setFullName($fullNameField);
        }

        return $leadContactsRespDto;
    }

    private function mapFieldsToResponse(Deal $deal): array
    {
        $fields = $deal->getFields();

        $fieldsArray = [];

        foreach ($fields as $field) {
            $fieldDto = (new LeadFieldRespDto())
                ->setType('string')
                ->setValue($field->getValue())
                ->setName($field->getName());

            $fieldsArray[] = $fieldDto;
        }

        return $fieldsArray;
    }

    private function mapOrderToResponse(Deal $deal): OrderRespDto // надо подумать тут, а то еще непонятно, какое дто лежит в ордере в базе
    {
        return new OrderRespDto();
    }
}
