<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\AuthenticateUser\Presentation;

use Nelmio\ApiDocBundle\Annotation\Model;
use NicolasLefevre\LeBonCode\Core\Presentation\Action;
use NicolasLefevre\LeBonCode\User\AuthenticateUser\Application\AuthenticateUserResult;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/login', name: 'authenticate_user', methods: ['POST'])]
#[OA\RequestBody(
    description: 'The request body to register a user',
    required: true,
    content: new OA\JsonContent(
        ref: new Model(type: AuthenticateUserRequest::class)
    )
)]
#[OA\Response(
    response: '200',
    description: 'User authenticated',
    content: new OA\JsonContent(
        properties: [
            'access_token' => new OA\Property(
                property: 'access_token',
                description: 'The JWT access token',
                type: 'string',
            ),
            'refresh_token' => new OA\Property(
                property: 'refresh_token',
                description: 'The UUID refresh token',
                type: 'string',
            ),
        ]
    )
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
final readonly class AuthenticateUserAction implements Action
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(
        #[MapRequestPayload] AuthenticateUserRequest $request,
        AuthenticateUserResult $result,
    ): Response {
        $this->messageBus->dispatch(AuthenticateUserCommandMapper::fromRequest($request));

        return new JsonResponse([
            'access_token' => $result->getAccessToken(),
            'refresh_token' => $result->getRefreshToken(),
        ], Response::HTTP_OK);
    }
}
