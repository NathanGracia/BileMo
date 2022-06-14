<?php

namespace App\Listener\Kernel;

use App\Normalizer\INormalizer;
use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelInterface;

class ExceptionListener
{

    /**
     * @var RewindableGenerator|INormalizer[]
     */
    private $normalizers;
    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct(RewindableGenerator $normalizers, KernelInterface $kernel)
    {
        $this->normalizers = $normalizers;
        $this->kernel = $kernel;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        foreach ($this->normalizers as $normalizer) {
            if ($normalizer->support($exception)) {
                $event->setResponse(
                    $normalizer->normalize($exception)
                );
                return;
            }
        }

        if ($this->kernel->getEnvironment() === 'prod') {
            $event->setResponse(
                new JsonResponse(['message' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR)
            );
        }
    }
}