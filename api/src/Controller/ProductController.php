<?php

namespace App\Controller;

use App\Entity\Product;
use App\Exception\JsonInvalidException;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use OpenApi\Annotations\Tag;
use App\Form\ProductType;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * List Product
     * List of products.
     * @Route("", name="product_index",methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns the rewards of an user"
     * )
     * @OA\Parameter(
     *     name="order",
     *     in="query",
     *     description="The field used to order rewards",
     *     @OA\Schema(type="string")
     * )
     */
       public function index(Request $request, ProductRepository $productRepository)
    {
        $nbElementsPerPage = $request->query->getInt('nbElementsPerPage', 10) % 40;
        $page = $request->query->getInt('page', 0);

        $productsPaginator = $productRepository->findAllPaginated($page, $nbElementsPerPage);

        $products = $productRepository->findAllPaginated($page, $nbElementsPerPage);

        return $this->json(
            [
                'products' => $products,
                'nbElementsPerPage' => $nbElementsPerPage,
                'page' => $page,
                'nbElements' => count($productsPaginator)

            ],
            Response::HTTP_OK

        );
    }

    /**
     * Create Product
     *
     * Permit to create a product.
     *
     * @Route("", name="product_new", methods={"POST"})4
     */
    public function new(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $json = json_decode($request->getContent(), true);
        if(json_last_error()){
           return  $this->json(["message" => "json invalid"], Response::HTTP_BAD_REQUEST);
        }

        $form->submit($json);

        if (!($form->isSubmitted() && $form->isValid())) {
            return $this->json(["message" => "form invalid"], Response::HTTP_BAD_REQUEST);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($product);
        $entityManager->flush();

        return $this->json(
            $product,
            Response::HTTP_CREATED

        );
    }

    /**
     * @Route("/{id}", name="product_show", methods={"GET"})
     * @param Product $product
     * @return Response
     */
    public function show(Product $product): Response
    {
        return $this->json(
            $product,
            Response::HTTP_OK,

        );
    }
}
