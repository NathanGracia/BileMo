<?php

namespace App\Exception;

use Exception;
use Symfony\Component\Form\FormInterface;
use Throwable;

class FormErrorException extends Exception
{
    private $form;

    public function __construct(FormInterface $form, string $message = "", int $code = 0, Throwable $previous = null)
    {
        $this->form = $form;
        parent::__construct($message, $code, $previous);
    }

    public function getForm(): FormInterface
    {
        return $this->form;
    }
}