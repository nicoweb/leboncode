<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Advert\GetAdvert\Infrastructure\Listener;

use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Error\AdvertDeletedError;
use NicolasLefevre\LeBonCode\Advert\GetAdvert\Domain\Error\AdvertNotFoundError;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

final class AdvertNotFoundSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HandlerFailedException) {
            $exception = $exception->getPrevious();
        }

        if (!$exception instanceof AdvertNotFoundError && !$exception instanceof AdvertDeletedError) {
            return;
        }

        $statusCode = Response::HTTP_NOT_FOUND;

        $payload = [
            'code' => $statusCode,
            'message' => 'advert_not_found',
        ];

        $event->setResponse(new JsonResponse(
            $payload,
            $statusCode,
        ));
    }
}
