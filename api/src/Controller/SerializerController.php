<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\Serializer\SerializationContext;

class SerializerController extends AbstractController
{
     /**
     * Returns a JsonResponse that uses the serializer component if enabled, or json_encode.
     */
    protected function json($data, int $status = 200, array $headers = [], array $context = [] ): JsonResponse
    {
        if ($this->container->has('jms_serializer')) {
            $json = $this->container->get('jms_serializer')->serialize( $data, 'json', SerializationContext::create()->setGroups($context['groups']));;

            return new JsonResponse($json, $status, $headers, true);
        }

        return parent::json($data, $status, $headers, $context);
    }

}