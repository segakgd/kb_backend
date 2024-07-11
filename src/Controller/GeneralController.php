<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Только для наследования!
 */
class GeneralController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
    ) {}

    /**
     * @throws BadRequestException
     */
    public function getValidDtoFromRequest(Request $request, string $className): object
    {
        $content = $request->getContent();

        $requestDto = $this->serializer->deserialize($content, $className, 'json');

        $errors = $this->validator->validate($requestDto);

        if (count($errors) > 0) {
            throw new BadRequestException(
                message: $errors->get(0)->getMessage(),
                code: Response::HTTP_BAD_REQUEST
            );
        }

        return $requestDto;
    }
}
