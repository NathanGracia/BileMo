<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SecurityController extends AbstractController
{
    /**
     * @Route("/api/token", name="security_token", methods={"POST"})
     */
    public function index(): Response
    {
        throw new Exception('Should not be reached');
    }
}
