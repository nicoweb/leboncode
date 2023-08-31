<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\CreateAdvert\Presentation;

use Nelmio\ApiDocBundle\Annotation\Model;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Entity\User;
use NicolasLefevre\LeBonCode\Core\Presentation\Action;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/adverts', name: 'create_advert', methods: ['POST'])]
#[IsGranted('ROLE_USER')]
#[OA\RequestBody(
    description: 'The request body to create an advert',
    required: true,
    content: new OA\JsonContent(
        ref: new Model(type: CreateAdvertRequest::class)
    )
)]
#[OA\Response(
    response: '201',
    description: 'Advert created',
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
                            example: 'postalCode',
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
final class CreateAdvertAction implements Action
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(
        #[MapRequestPayload] CreateAdvertRequest $request,
        #[CurrentUser] User $user,
    ): Response {
        $command = CreateAdvertCommandFactory::fromRequest($request, $user);

        $this->messageBus->dispatch($command);

        return new Response(null, Response::HTTP_CREATED);
    }
}
