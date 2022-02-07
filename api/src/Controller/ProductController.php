<?php

namespace App\Controller;

use App\Entity\Product;

use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations\Tag;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product")
 * @Tag(name="Product")
 * @Security(name="Bearer")
 */
class ProductController extends SerializerController
{
    /**
     * List Product
     * 
     * List of products.
     * @Route("", name="product_index",methods={"GET"})
     * 
     * @OA\Parameter(name="page", in="query", @OA\Schema(type="integer"))
     * @OA\Parameter(name="nbElementsPerPage", in="query", @OA\Schema(type="integer"))
     * 
     * @OA\Response(
     *     response=200,
     *     description="Returns the products"
     *    
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
                'products' => iterator_to_array($products) ,
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
     * @OA\RequestBody(@Model(type=ProductType::class))
     * @OA\Response(
     *     response=201,
     *     description="product created"
     * )
     * @Route("", name="product_new", methods={"POST"})
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
     * Return a product.
     * 
     * Return a specific product by id.
     * @Route("/{id}", name="product_show", methods={"GET"})
     * @param Product $product
     * @return Response 
     * @OA\Response(
     *     response=200,
     *      description="show specific product"
     * )
     */
    public function show(Product $product): Response
    {
        return $this->json(
            $product,
            Response::HTTP_OK,

        );
    }
}
