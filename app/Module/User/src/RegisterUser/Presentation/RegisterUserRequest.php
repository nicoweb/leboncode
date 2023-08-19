<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\User\RegisterUser\Presentation;

use OpenApi\Attributes as OA;

final readonly class RegisterUserRequest
{
    public function __construct(
        #[OA\Property(
            property: 'id',
            description: 'The id of the user',
            example: 'cec4ddc1-78cc-458b-938a-2ee7bafd53fc'
        )]
        public string $id = '',

        #[OA\Property(
            property: 'email',
            description: 'The email of the user',
            example: 'jane.doe@example.com'
        )]
        public string $email = '',

        #[OA\Property(
            property: 'password',
            description: 'The password of the user',
            example: 'password123'
        )]
        public string $password = '',

        #[OA\Property(
            property: 'firstname',
            description: 'The firstname of the user',
            example: 'Jane'
        )]
        public string $firstname = '',

        #[OA\Property(
            property: 'lastname',
            description: 'The lastname of the user',
            example: 'Doe'
        )]
        public string $lastname = '',
    ) {
    }
}
