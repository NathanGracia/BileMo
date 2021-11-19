<?php

namespace App\Controller;


use App\Entity\ClientCustomer;
use App\Exception\JsonInvalidException;


use App\Form\ClientCustomerType;
use App\Repository\ClientCustomerRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/clientCustomer")
 */
class ClientCustomerController extends AbstractController
{
    /**
     * List ClientCustomer
     * 
     * List of products.
     * @Route("", name="clientCustomer_index",methods={"GET"})
     */
       public function index(Request $request, ClientCustomerRepository $clientCustomerRepository)
    {
        $nbElementsPerPage = $request->query->getInt('nbElementsPerPage', 10) % 40;
        $page = $request->query->getInt('page', 0);

        $clientCustomersPaginator = $clientCustomerRepository->findAllPaginated($page, $nbElementsPerPage);

        $clientCustomers = $clientCustomerRepository->findAllPaginated($page, $nbElementsPerPage);

        return $this->json(
            [
                'clientCustomers' => $clientCustomers,
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
     *
     * @Route("", name="clientCustomer_new", methods={"POST"})
     */
    public function new(Request $request)
    {
        $clientCustomer = new \App\Entity\ClientCustomer();
        $form = $this->createForm(ClientCustomerType::class, $clientCustomer);

        $json = json_decode($request->getContent(), true);
        if(json_last_error()){
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
     * @Route("/{id}", name="clientCustomer_show", methods={"GET"})
     * @param ClientCustomer $clientCustomer
     * @return Response
     */
    public function show(ClientCustomer $clientCustomer): Response
    {
        return $this->json(
            $clientCustomer,
            Response::HTTP_OK

        );
    }
    /**
     * @Route("/{id}", name="clientCustomer_delete", methods={"DELETE"})
     * @param ClientCustomer $clientCustomer
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
