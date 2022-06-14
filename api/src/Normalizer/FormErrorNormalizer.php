<?php

namespace App\Normalizer;

use App\Exception\FormErrorException;
use Exception;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class FormErrorNormalizer extends ANormalizer
{
    public function getExceptionSupported(): array
    {
        return [
            FormErrorException::class
        ];
    }

    public function normalize(Exception $exception): Response
    {
        if (!$this->support($exception)) {
            throw new Exception(
                "Exception (" . get_class($exception) . ") is not supported by normalizer (" . get_class($this) . ")"
            );
        }

        /** @var FormErrorException $exception */
        return new JsonResponse($this->serializeErrors($exception->getForm()), Response::HTTP_BAD_REQUEST);
    }

    public function serializeErrors(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors() as $formError) {
            $errors['globals'][] = $formError->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->subSerializeErrors($childForm)) {
                    $errors['fields'][$childForm->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }

    private function subSerializeErrors(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->subSerializeErrors($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }
}