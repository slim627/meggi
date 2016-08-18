<?php

namespace Meggi\IndexBundle\Controller;

use Meggi\IndexBundle\Entity\OrderItem;
use Meggi\IndexBundle\Entity\Orders;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends Controller
{
    /**
     * @Route("/all-product", name="all_products")
     * @Template()
     */
    public function allProductsAction(Request $request)
    {
        if($request->get('brand')){
//            $brand = $request->get('brand');

            $brand = $this->getDoctrine()->getRepository('MeggiIndexBundle:Brand')->findOneBy(['url' => $request->get('brand')]);

            $qb = $this->getDoctrine()->getManager();

            $query = $qb->createQueryBuilder()
                ->select('products', 'brand')
                ->from('MeggiIndexBundle:Product', 'products')
                ->leftJoin('products.brand', 'brand')
                ->andWhere('brand.url = :brand')
                ->setParameters(['brand' => $brand->getUrl()]);

            $products = $query->getQuery()->getResult();

            if(!$products)
            {
                throw new NotFoundHttpException();
            }

            $query = $qb->createQueryBuilder()
                ->select('products', 'categories', 'brand')
                ->from('MeggiIndexBundle:Category', 'categories')
                ->leftJoin('categories.products', 'products')
                ->leftJoin('products.brand', 'brand')
                ->andWhere('brand.url = :brand')
                ->setParameters(['brand' => $brand->getUrl()]);

            $categories = $query->getQuery()->getResult();

            if(!$categories)
            {
                throw new NotFoundHttpException();
            }

            return [
                'products' => $products,
                'categories' => $categories,
                'brand' => $brand,
                'category' => null,
            ];
        }
        else{
            $products = $this->getDoctrine()->getRepository('MeggiIndexBundle:Product')->findAll();

            $qb = $this->getDoctrine()->getManager();
            $query = $qb->createQueryBuilder()
                ->select('products', 'categories')
                ->from('MeggiIndexBundle:Category', 'categories')
                ->leftJoin('categories.products', 'products')
                ->andWhere('products != 0');

            $categories = $query->getQuery()->getResult();

            return [
                'products' => $products,
                'categories' => $categories,
                'brand' => null,
                'category' => null,
            ];
        }
    }

    /**
     * @Route("/products", name="category_products")
     * @Template(template="MeggiIndexBundle:Product:allProducts.html.twig")
     */
    public function categoryProducts(Request $request)
    {
        $category = $this->getDoctrine()->getRepository('MeggiIndexBundle:Category')->findOneBy(['url' => $request->get('category')]);
        $qb = $this->getDoctrine()->getManager();

        if($request->get('brand')){

            $brand = $this->getDoctrine()->getRepository('MeggiIndexBundle:Brand')->findOneBy(['url' => $request->get('brand')]);

            $query = $qb->createQueryBuilder()
                ->select('products', 'categories', 'brand')
                ->from('MeggiIndexBundle:Category', 'categories')
                ->leftJoin('categories.products', 'products')
                ->leftJoin('products.brand', 'brand')
                ->andWhere('brand.url = :brand')
                ->setParameters(['brand' => $brand->getUrl()]);

            $categories = $query->getQuery()->getResult();

            if(!$categories)
            {
                throw new NotFoundHttpException();
            }

            if($request->get('category') == 'all-products'){
                $query = $qb->createQueryBuilder()
                    ->select('products', 'brand')
                    ->from('MeggiIndexBundle:Product', 'products')
                    ->leftJoin('products.brand', 'brand')
                    ->andWhere('brand.url = :brand')
                    ->setParameters(['brand' => $brand->getUrl()]);

                $products = $query->getQuery()->getResult();
            }
            else{
                $query = $qb->createQueryBuilder()
                    ->select('products', 'categories', 'brand')
                    ->from('MeggiIndexBundle:Product', 'products')
                    ->leftJoin('products.categories', 'categories')
                    ->leftJoin('products.brand', 'brand')
                    ->andWhere('brand.url = :brand')
                    ->andWhere('categories.url = :category')
                    ->setParameters(['brand' => $request->get('brand'), 'category' => $request->get('category')]);

                $products = $query->getQuery()->getResult();

                if(!$products)
                {
                    throw new NotFoundHttpException();
                }
            }

            return [
                'categories' => $categories,
                'products' => $products,
                'category' => $category,
                'brand' => $brand,
            ];
        }
        else{
            $query = $qb->createQueryBuilder()
                ->select('products', 'categories')
                ->from('MeggiIndexBundle:Category', 'categories')
                ->leftJoin('categories.products', 'products')
                ->andWhere('products != 0');

            $categories = $query->getQuery()->getResult();

            if($request->get('category') == 'all-products'){
                $products = $this->getDoctrine()->getRepository('MeggiIndexBundle:Product')->findAll();
            }
            else{
                $query = $qb->createQueryBuilder()
                    ->select('products', 'categories')
                    ->from('MeggiIndexBundle:Product', 'products')
                    ->leftJoin('products.categories', 'categories')
                    ->andWhere('categories.url = :category')
                    ->setParameters(['category' => $request->get('category')]);

                $products = $query->getQuery()->getResult();

                if(!$products)
                {
                    throw new NotFoundHttpException();
                }
            }

            return [
                'categories' => $categories,
                'category' => $category,
                'products' => $products,
                'brand' => null
            ];
        }
    }

    /**
     * @Route("/product/{url}", name="product")
     * @Template()
     */
    public function oneProductAction(Request $request, $url)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQueryBuilder()
            ->select('products', 'brand')
            ->from('MeggiIndexBundle:Brand', 'brand')
            ->leftJoin('brand.products', 'products')
            ->andWhere('products.url = :product')
            ->setParameters(['product' => $url]);

        $brand = $query->getQuery()->getSingleResult();

        $query = $em->createQueryBuilder()
            ->select('products', 'brand')
            ->from('MeggiIndexBundle:Product', 'products')
            ->leftJoin('products.brand', 'brand')
            ->andWhere('brand.url = :brand')
            ->andWhere('products.url != :product')
            ->setParameters(['product' => $url, 'brand' => $brand->getUrl()]);

        $products = $query->getQuery()->getResult();

        $form = $this->createFormBuilder()
            ->add('quantity','integer',['label' => false])
            ->add('id','hidden',['label' => false])
            ->getForm();

        $form->handleRequest($request);

        if($form->isValid()) {
            $data = $form->getData();

            $session = $this->get('session');

            $basket = empty($session->get('basket')) ? $session->set('basket', []) : $session->get('basket');

            if(!empty($basket) && array_key_exists($data['id'], $basket)){
                $basket[$data['id']] += $data['quantity'];
            }else{
                $basket[$data['id']] = intval($data['quantity']);
            }

            $session->set('quantityAssortment', count($basket));
            $session->set('basket', $basket);

            $referer = $request->headers->get('referer');

            return $this->redirect($referer);
        }

        $product = $this->getDoctrine()->getRepository('MeggiIndexBundle:Product')->findOneBy(['url' => $url]);

        if(!$product)
        {
            throw new NotFoundHttpException();

        }

        return [
            'product' => $product,
            'form' => $form->createView(),
            'products' => $products,
            'brand' => $brand
        ];
    }
}
