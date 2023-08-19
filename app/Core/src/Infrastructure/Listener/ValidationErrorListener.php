<?php

declare(strict_types=1);

namespace NicolasLefevre\LeBonCode\Core\Infrastructure\Listener;

use NicolasLefevre\LeBonCode\Core\Domain\Error\ValidationError;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

final class ValidationErrorListener implements EventSubscriberInterface
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

        if (!$exception instanceof ValidationError) {
            return;
        }

        $violations = [];

        foreach ($exception->violations->items as $violation) {
            $violations[] = ['field' => $violation->propertyPath, 'error' => $violation->message];
        }

        $payload = [
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'message' => 'Validation error',
            'violations' => $violations,
        ];

        $event->setResponse(new JsonResponse(
            $payload,
            Response::HTTP_UNPROCESSABLE_ENTITY,
        ));
    }
}
