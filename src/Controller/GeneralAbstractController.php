<?php

namespace App\Controller;

use App\Repository\Dto\PaginateDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Только для наследования!
 */
class GeneralAbstractController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
    ) {}


    /**
     * @throws BadRequestException
     */
    public function getValidDtoFromFormDataRequest(Request $request, string $className): object
    {
        $content = $request->query->all();

        $requestDto = $this->serializer->denormalize(
            data: $content,
            type: $className,
        );

        $errors = $this->validator->validate($requestDto);

        if (count($errors) > 0) {
            throw new BadRequestException(
                message: $errors->get(0)->getMessage(),
                code: Response::HTTP_BAD_REQUEST
            );
        }

        return $requestDto;
    }

    /**
     * @throws BadRequestException
     */
    public function getValidDtoFromRequest(Request $request, string $className): object
    {
        $content = $request->getContent();

        $requestDto = $this->serializer->deserialize(
            data: empty($content) ? '{}' : $content,
            type: $className,
            format: 'json',
        );

        $errors = $this->validator->validate($requestDto);

        if (count($errors) > 0) {
            throw new BadRequestException(
                message: $errors->get(0)->getMessage(),
                code: Response::HTTP_BAD_REQUEST
            );
        }

        return $requestDto;
    }

    public static function makePaginateResponse(array $items, PaginateDto $paginate): array
    {
        return [
            'items'    => $items,
            'paginate' => [
                'currentPage' => $paginate->getCurrentPage(),
                'lastPage'    => $paginate->getLastPage(),
                'nextPage'    => $paginate->getNextPage(),
                'totalItems'  => $paginate->getTotalItems(),
                'totalPages'  => $paginate->getTotalPages(),
            ],
        ];
    }
}
