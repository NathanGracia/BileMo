<?php

namespace App\Controller;


use App\Entity\ClientCustomer;
use App\Exception\JsonInvalidException;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations\Tag;
use App\Form\ClientCustomerType;
use App\Repository\ClientCustomerRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @Route("/clientCustomer")
 * @Tag(name="ClientCustomer")
 * @Security(name="Bearer")
 */
class ClientCustomerController extends SerializerController
{
    /**
     * List ClientCustomer
     * 
     * List of customers.
     * @Route("", name="clientCustomer_index",methods={"GET"})
     * @OA\Parameter(name="page", in="query", @OA\Schema(type="integer"))
     * @OA\Parameter(name="nbElementsPerPage", in="query", @OA\Schema(type="integer"))
     * 
     * @OA\Response(
     *      response=200,
     *      description="Returns the list of client customers"
     *    
     * )
     */
    public function index(Request $request, ClientCustomerRepository $clientCustomerRepository)
    {
        $nbElementsPerPage = $request->query->getInt('nbElementsPerPage', 10) % 40;
        $page = $request->query->getInt('page', 0);

        $clientCustomersPaginator = $clientCustomerRepository->findAllPaginated($page, $nbElementsPerPage);

        $clientCustomers = $clientCustomerRepository->findAllPaginated($page, $nbElementsPerPage);

        return $this->json(
            [
                'clientCustomers' => iterator_to_array($clientCustomers),
                'nbElementsPerPage' => $nbElementsPerPage,
                'page' => $page,
                'nbElements' => count($clientCustomersPaginator)

            ],
            Response::HTTP_OK

        );
    }

    /**
     * Create ClientCustomer
     *
     * Permit to create a clientCustomer.
     * @OA\RequestBody(@Model(type=ClientCustomerType::class))
     * @OA\Response(
     *     response=201,
     *     description="client customer created"
     * )

     * @Route("", name="clientCustomer_new", methods={"POST"})
     */
    public function new(Request $request)
    {
        $clientCustomer = new \App\Entity\ClientCustomer();
        $form = $this->createForm(ClientCustomerType::class, $clientCustomer);

        $json = json_decode($request->getContent(), true);
        if (json_last_error()) {
            return  $this->json(["message" => "json invalid"], Response::HTTP_BAD_REQUEST);
        }

        $form->submit($json);

        if (!($form->isSubmitted() && $form->isValid())) {
            return $this->json(["message" => "form invalid"], Response::HTTP_BAD_REQUEST);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($clientCustomer);
        $entityManager->flush();

        return $this->json(
            $clientCustomer,
            Response::HTTP_CREATED

        );
    }

    /**
     * Return a client customer.
     * 
     * Return a specific sclient customer by id.
     * @Route("/{id}", name="clientCustomer_show", methods={"GET"})
     * @param ClientCustomer $clientCustomer
     * @return Response
     *    @OA\Response(
     *     response=200,
     *      description="show specific client customer"
     * )
     */
    public function show(ClientCustomer $clientCustomer): Response
    {
        return $this->json(
            $clientCustomer,
            Response::HTTP_OK

        );
    }
    /**
     * Delete ClientCustomer
     *
     * Permit to delete a clientCustomer.
     * @Route("/{id}", name="clientCustomer_delete", methods={"DELETE"})
     * @param ClientCustomer $clientCustomer
     * 
     *  @OA\Response(
     *     response=301,
     *      description="client customer deleted"
     * )
     * @return Response
     */
    public function delete(ClientCustomer $clientCustomer): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($clientCustomer);
        $entityManager->flush();
        return $this->json(
            null,
            Response::HTTP_MOVED_PERMANENTLY

        );
    }
}
