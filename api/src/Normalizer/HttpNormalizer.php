<?php

namespace App\Normalizer;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HttpNormalizer extends ANormalizer
{

    public function getExceptionSupported(): array
    {
        return [
            HttpException::class
        ];
    }

    public function normalize(Exception $exception): Response
    {
        if (!$this->support($exception)) {
            throw new Exception(
                "Exception (" . get_class($exception) . ") is not supported by normalizer (" . get_class($this) . ")"
            );
        }

        $explode = explode('\\', get_class($exception));
        $classFullName = $explode[count($explode) - 1];
        return new JsonResponse(
            ['message' => str_replace('HttpException', '', $classFullName)],
            $exception->getStatusCode(),
            $exception->getHeaders()
        );
    }
}