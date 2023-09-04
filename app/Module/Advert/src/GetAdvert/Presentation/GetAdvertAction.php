<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\GetAdvert\Presentation;

use Nelmio\ApiDocBundle\Annotation\Model;
use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Handler\GetAdvertQuery;
use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Handler\QueryHandler;
use NicolasLefevre\LeBonCode\Core\Presentation\Action;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/adverts/{id}', name: 'get_advert', methods: ['GET'])]
#[IsGranted('ROLE_USER')]
#[OA\Response(
    response: '200',
    description: 'Advert found',
    content: new OA\JsonContent(
        ref: new Model(type: GetAdvertResource::class),
    )
)]
#[OA\Response(
    response: '404',
    description: 'Advert not found',
    content: new OA\JsonContent(
        properties: [
            'code' => new OA\Property(
                property: 'code',
                type: 'integer',
                example: 404,
            ),
            'message' => new OA\Property(
                property: 'message',
                type: 'string',
                example: 'advert_not_found',
            ),
        ],
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
                            example: 'id',
                        ),
                        'error' => new OA\Property(
                            property: 'error',
                            type: 'string',
                            example: 'invalid',
                        ),
                    ],
                ),
            ),
        ],
    ),
)]
final readonly class GetAdvertAction implements Action
{
    public function __construct(
        private QueryHandler $queryHandler,
    ) {
    }

    public function __invoke(string $id): Response
    {
        $advert = $this->queryHandler->handle(new GetAdvertQuery($id));

        return new JsonResponse(
            AdvertToResourceMapper::map($advert),
            Response::HTTP_OK,
        );
    }
}
