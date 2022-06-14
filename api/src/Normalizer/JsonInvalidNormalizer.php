<?php

namespace App\Normalizer;

use App\Exception\JsonInvalidException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class JsonInvalidNormalizer extends ANormalizer
{

    public function getExceptionSupported(): array
    {
        return [
            JsonInvalidException::class
        ];
    }

    public function normalize(Exception $exception): Response
    {
        if (!$this->support($exception)) {
            throw new Exception(
                "Exception (" . get_class($exception) . ") is not supported by normalizer (" . get_class($this) . ")"
            );
        }

        return new JsonResponse(['message' => 'Json invalid'], Response::HTTP_BAD_REQUEST);
    }
}