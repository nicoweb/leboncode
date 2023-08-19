<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\RegisterUser\Presentation;

use Nelmio\ApiDocBundle\Annotation\Model;
use NicolasLefevre\LeBonCode\Core\Presentation\Action;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/users', name: 'register_user', methods: ['POST'])]
#[OA\RequestBody(
    description: 'The request body to register a user',
    required: true,
    content: new OA\JsonContent(
        ref: new Model(type: RegisterUserRequest::class)
    )
)]
#[OA\Response(
    response: '204',
    description: 'Resource created',
    content: null,
)]
#[OA\Response(
    response: '422',
    description: 'Validation error',
    content: new OA\JsonContent(
        properties: [
            'code' => new OA\Property(
                property: 'code',
                type: 'integer',
                example: 422,
            ),
            'message' => new OA\Property(
                property: 'message',
                type: 'string',
                example: 'Validation error',
            ),
            'violations' => new OA\Property(
                property: 'violations',
                type: 'array',
                items: new OA\Items(
                    properties: [
                        'field' => new OA\Property(
                            property: 'field',
                            type: 'string',
                            example: 'email',
                        ),
                        'error' => new OA\Property(
                            property: 'error',
                            type: 'string',
                            example: 'not_valid',
                        ),
                    ],
                ),
            ),
        ],
    ),
)]
final readonly class RegisterUserAction implements Action
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(#[MapRequestPayload] RegisterUserRequest $request): Response
    {
        $this->messageBus->dispatch(RegisterUserCommandMapper::fromRequest($request));

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
