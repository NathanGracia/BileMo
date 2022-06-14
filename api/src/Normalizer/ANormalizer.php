<?php

namespace App\Normalizer;

use Exception;

abstract class ANormalizer implements INormalizer
{
    public function support(Exception $exception): bool
    {
        foreach ($this->getExceptionSupported() as $exceptionSupported) {
            if ($exception instanceof $exceptionSupported) {
                return true;
            }
        }

        return false;
    }

    abstract public function getExceptionSupported(): array;
}