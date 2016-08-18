<?php

namespace Meggi\IndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class BasketController extends Controller
{
    /**
     * Изменение кол-ва продуктов в хедере корзины
     * @Route("/change-header-basket", name="change_header_basket")
     * @Template()
     */
    public function basketHeaderAction(Request $request)
    {
        $finalCostForAllProducts = 0;
        $session = $this->get('session');
        $basket = $session->get('basket');

        $quantityAssortment = $session->get('quantityAssortment') ? $session->get('quantityAssortment') : 0;

        if(!empty($basket)){
            foreach($basket as $id=>$quantity){
                $product = $this->getDoctrine()->getRepository('MeggiIndexBundle:Product')->find(intval($id));
                $finalCostForAllProducts += intval($product->getCost()) * intval($quantity);
            }
        }

        if(!empty($request->get('param'))){

            $prod_id = $request->get('prod_id');
            unset($basket[intval($prod_id)]);
            $session->set('basket', $basket);
            $quantityAssortment = count($basket);

            return new JsonResponse([
                'quantityAssortment'      => $quantityAssortment,
                'finalCostForAllProducts' => $finalCostForAllProducts,]);
        }

        return [
            'quantityAssortment'      => $quantityAssortment,
            'finalCostForAllProducts' => $finalCostForAllProducts,
        ];
    }

    /**
     * @Route("/remove-from-basket", name="remove_from_basket")
     */
    public function removeFromBasketAction(Request $request)
    {
        $products = [];

        $session = $this->get('session');
        $basket = $session->get('basket');
        $prod_id = $request->get('prod_id');

        unset($basket[intval($prod_id)]);
        $session->set('basket', $basket);
        foreach($basket as $product_id => $quantity){
            $product = $this->getDoctrine()->getRepository('MeggiIndexBundle:Product')->find(intval($product_id));
            $products[] = ['product' => $product, 'quantity' => $quantity];
        }
        $session->set('quantityAssortment', count($basket));

        return new JsonResponse($products);
    }

    /**
     * Просмотр корзины
     * @Route("/show-basket", name="show_basket")
     * @Template()
     */
    public function basketAction()
    {
        $session = $this->get('session');
        $basket = $session->get('basket');
        $finalCostForAllProducts = 0;
        $prodItem = null;

        if(!empty($basket)){
            foreach($basket as $id => $quantity){
                $product = $this->getDoctrine()->getRepository('MeggiIndexBundle:Product')->find(intval($id));
                $prodItem[] = [$product, $quantity];
                $finalCostForAllProducts += intval($product->getCost()) * intval($quantity);

            }
        }else{
            $this->get('session')->getFlashBag()->add('empty-basket', 'К сожалению корзина пока пуста');
        }

        return [
            'prodItem' => $prodItem,
            'finalCostForAllProducts' => $finalCostForAllProducts,
        ];

    }

    /**
     * @Route("/basket-load/{orderId}", name="basket_load")
     */
    public function loadAction(Request $request, $orderId)
    {
        $user = $this->getUser();
        if(empty($user)){
            return $this->redirect($request->headers->get('referer'));
        }

        $order = $this->getDoctrine()
            ->getManager()
            ->getRepository('MeggiIndexBundle:Orders')
            ->findOneBy(['user' => $user, 'id' => $orderId]);

        if(!$order)
        {
            throw new NotFoundHttpException('Order not found!');
        }

        $basket = [];
        foreach($order->getItems() as $item )
        {
            $basket[$item->getProduct()->getId()] = $item->getQuantity();
        }

        $session = $this->get('session');
        $session->set('basket', $basket);
        $session->set('orderId', $orderId);

        return $this->redirect($this->generateUrl('show_basket'));
    }

    /**
     * @Route("/change-product-quantity", name="change_product_quantity")
     */
    public function changeProductQuantityAction(Request $request)
    {
        $prod_id = intval($request->get('prod_id'));
        $quantity = intval($request->get('quantity'));
        $product = $this->getDoctrine()->getRepository('MeggiIndexBundle:Product')->find(intval($prod_id));

        $session = $this->get('session');
        $basket = $session->get('basket');
        $basket[$prod_id] = $quantity;

        $session->set('basket', $basket);

        return new JsonResponse(['prod_id' => $prod_id, 'cost' => $product->getCost()]);
    }
}
