<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
class SerializerController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        
    }
     /**
     * Returns a JsonResponse that uses the serializer component if enabled, or json_encode.
     */
    protected function json($data, int $status = 200, array $headers = [], array $context = [] ): JsonResponse
    {   
      
         $groups = $context['groups']?? [];
            $json = $this->serializer->serialize( $data, 'json',empty($groups) ? null : SerializationContext::create()->setGroups($groups));

            return new JsonResponse($json, $status, $headers, true);
    

       
    }

}