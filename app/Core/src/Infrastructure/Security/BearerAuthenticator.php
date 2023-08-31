<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Infrastructure\Security;

use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use NicolasLefevre\LeBonCode\Core\Domain\Service\JwtDecoder;
use NicolasLefevre\LeBonCode\Core\Domain\ValueObject\Email;
use NicolasLefevre\LeBonCode\Core\Infrastructure\Persistence\Doctrine\Repository\UserDoctrineRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CredentialsExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\AccessToken\Oidc\Exception\InvalidSignatureException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Throwable;

final class BearerAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly UserDoctrineRepository $userRepository,
        private readonly JwtDecoder $jwtDecoder,
    ) {
    }

    public function supports(Request $request): ?bool
    {
        $authorizationHeader = $request->headers->get('authorization');

        return is_string($authorizationHeader)
            && str_starts_with($authorizationHeader, 'Bearer ');
    }

    public function authenticate(Request $request): Passport
    {
        $authorizationHeader = $request->headers->get('authorization');

        if (!is_string($authorizationHeader)) {
            throw new CustomUserMessageAuthenticationException('invalid_credentials');
        }

        $jwt = substr($authorizationHeader, 7);

        try {
            $data = $this->jwtDecoder->decode($jwt);
        } catch (ExpiredException) {
            throw new CredentialsExpiredException();
        } catch (SignatureInvalidException) {
            throw new InvalidSignatureException();
        } catch (Throwable) {
            throw new CustomUserMessageAuthenticationException('invalid_credentials');
        }

        if (!$this->userRepository->isUserExists(Email::fromString($data->email))) {
            throw new CustomUserMessageAuthenticationException('invalid_credentials');
        }

        return new SelfValidatingPassport(new UserBadge($data->email));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
