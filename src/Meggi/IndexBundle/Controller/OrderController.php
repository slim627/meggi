<?php

namespace Meggi\IndexBundle\Controller;

use Meggi\IndexBundle\Entity\OrderItem;
use Meggi\IndexBundle\Entity\Orders;
use Meggi\IndexBundle\Entity\Product;
use Meggi\IndexBundle\Twig\DateExtension;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use PhpOffice\PhpWord\PhpWord;
use Meggi\IndexBundle\Helper\Num2StrHelper;
use Meggi\IndexBundle\Twig\AmountExtension;

class OrderController extends Controller
{
    /**
     * @Route("/confirm", name="confirm")
     * @Template()
     */
    public function confirmAction(Request $request)
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect($this->generateUrl('login_register_form'));
        }

        $products = [];
        $costResult = 0;
        $costDiscountResult = 0;
        $session = $this->get('session');
        $basket = $session->get('basket');
        if(!empty($basket)){
            foreach($basket as $product=>$quantity){
                $prodObj = $this->getDoctrine()->getRepository('MeggiIndexBundle:Product')->find(intval($product));
                $products[] = [$prodObj, $quantity];
                $costResult += ($prodObj->getCost() * $quantity);
                $costDiscountResult += (round($prodObj->getCost()* 0.95) * $quantity );
            }
        }

        if(empty($products)){
            return $this->redirect($this->generateUrl('account'));
        }

        return [
            'products' => $products,
            'costResult' => $costResult,
            'costDiscountResult' => $costDiscountResult,
        ];
    }

    /**
     * @param Request $request
     * @Route("/close-confirm", name="close_confirm")
     * @Template()
     * @return array
     */
    public function closeConfirmAction(Request $request)
    {
        $sum = 0;
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');
        $basket = $session->get('basket');
        $orderId = $session->get('orderId');
        $order = null;
        if($orderId){
            $order = $this->getDoctrine()
                ->getRepository('MeggiIndexBundle:Orders')
                ->findOneBy(['id' => $orderId]);
        }

        $order = ($order) ? $order : new Orders();
        $order->clearItems();

        $dataDelivery = intval($request->get('addr'));
        $dataMessage = $request->get('message');

        if(!empty($basket)){
            foreach($basket as $prod_id=>$quantity){
                $product = $this->getDoctrine()->getRepository('MeggiIndexBundle:Product')->find(intval($prod_id));
                $item = new OrderItem();
                $item->setProduct($product);
                $item->setQuantity(intval($quantity));
                $item->setOrder($order);
                $product->addItem($item);
                $order->addItem($item);
                $em->persist($item);

                if($dataDelivery == Orders::DELIVERY_BY_MYSELF){
                    $delivery_discount = $this->container->getParameter('delivery_discount');
                    $sum += round($product->getCost() * (1 - ($delivery_discount / 100))) * $quantity;
                }else{
                    $sum += intval($product->getCost()) * $quantity;
                }
            }

            $order->setUser($user);
            $order->setStatus(Orders::STATUS_BILLED);
            $order->setDelivery($dataDelivery);
            $order->setDeliveryAddress($request->get('delivery-address'));
            $order->setMessage($dataMessage);
            $order->setFullSumm($sum);
            $em->persist($order);
            $em->persist($product);
            $em->flush();
        }

        $session->set('basket', 0);
        $session->set('quantityAssortment', 0);

        return [];
    }

    /**
     * @Route("/account", name="account")
     * @Template()
     */
    public function accountAction(Request $request)
    {
        $user = $this->getUser();
        if(empty($user)){
            return $this->redirect($this->generateUrl('login_register_form'));
        }

        $is_archive = (bool)$request->get('archive', false);
        $orders = $this->getDoctrine()->getRepository('MeggiIndexBundle:Orders')->getOrdersByUser($user);

        // Check died orders
        $em = $this->getDoctrine()->getManager();
        foreach($orders as $order){
            if($order->getStatus() == Orders::STATUS_BILLED){
                $delta = $this->container->getParameter('time_for_order_payment_complete');
                if(!DateExtension::timeRemainingFilter($order->getCreatedAt(), $delta)){
                    $order->setStatus(Orders::STATUS_OVERDUE);
                    $em->persist($order);
                }
            }
        }
        $em->flush();

        $form = $this->createApproveForm();

        return [
            'user'   => $user,
            'orders' => $orders,
            'is_archive' => $is_archive,
            'form' => $form->createView(),
        ];
    }

    /**
     * @param int $orderId
     *
     * @Route("/delete-order/{orderId}", name="delete_order")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteOrder($orderId)
    {
        $user = $this->getUser();

        $this->getDoctrine()
            ->getRepository('MeggiIndexBundle:Orders')
            ->deleteOrder(intval($orderId), $user);

        return $this->redirect($this->generateUrl('account'));
    }

    /**
     * @Route("/archiveOrder/{orderId}", name="order_move_to_archive")
     * @Template()
     */
    public function moveOrderToArchiveAction(Request $request, $orderId)
    {
        $user = $this->getUser();
        if(empty($user)){
            return $this->redirect($this->generateUrl('login_register_form'));
        }

        $em = $this->getDoctrine()->getManager();

        $order = $em->getRepository('MeggiIndexBundle:Orders')
            ->findOneBy(['id' => $orderId, 'user' => $user]);

        if(!$order)
        {
            throw new NotFoundHttpException('Order not found!');
        }

        $order->setIsArchive(true);

        $em->persist($order);
        $em->flush();

        return $this->redirect($this->generateUrl('account'));
    }

    /**
     * @Route("/approve-payment", name="approve_payment")
     * @Template()
     */
    public function approvePaymentAction(Request $request)
    {
        $user = $this->getUser();
        if (empty($user)) {
            return $this->redirect($this->generateUrl('login_register_form'));
        }

        $form = $this->createApproveForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $em = $this->getDoctrine()->getManager();

            $order = $this->getDoctrine()
                ->getRepository('MeggiIndexBundle:Orders')
                ->findOneBy(['id' => $data['order_id']]);

            $order->setPaymentNumber($data['number']);
            $order->setPaymentDate($data['date']);
            $order->setPaymentSum($data['sum']);
//            $order->setStatus(Orders::STATUS_WAIT);

            $em->persist($order);
            $em->flush();

            $mail = $this->getDoctrine()->getRepository('MeggiIndexBundle:Config')->findOneBy(['keyValue' => 'EMAIL_TO']);
            $stringOfEmails = $mail->getValue();
            $mailArray = explode(',', $stringOfEmails);
            foreach($mailArray as $email) {
                $removeSpaces = $string = preg_replace("/\s|&nbsp;/",'',$email);
                $finalEmail = strip_tags($removeSpaces);
                $mailer = $this->get('mailer');
                $message = \Swift_Message::newInstance()
                    ->setSubject('Подтверждение заказа')
                    ->setFrom($user->getEmail())
                    ->setTo($finalEmail)
                    ->setBody('Клиент ОАО "Компания" оплатил заказ. Проверьте оплату и подтвердите ее в системе управления сайтом.');
                ;
                $mailer->send($message);
            }


        }

        return $this->redirect($this->generateUrl('account'));
    }

    /**
     * @Route("/generate-invoice/{orderId}", name="order_generate_invoice")
     * @Template()
     */
    public function generateInvoiceAction(Request $request, $orderId)
    {
        $date = new \DateTime('now');
        $user = $this->getUser();
        if(empty($user)){
            return $this->redirect($this->generateUrl('login_register_form'));
        }

        $criteria = ['id' => $orderId, 'user' => $user];

        $order = $this->getDoctrine()->getRepository('MeggiIndexBundle:Orders')->findOneBy($criteria);

        $kernel = $this->get('kernel');
        $path = $kernel->locateResource('@MeggiIndexBundle/Resources/templates/expense.docx');

        $phpWord = new PhpWord();
        $templateProcessor = $phpWord->loadTemplate($path);
        $templateProcessor->setValue('count', $order->getId());
        $templateProcessor->setValue('accountId', AmountExtension::agreementFormat($user->getId()));
        $templateProcessor->setValue('companyName', $user->getCompanyName());
        $templateProcessor->setValue('date', $date->format('d.m.Y'));
        $templateProcessor->setValue('accDate', $user->getCreatedAt()->format('d.m.Y'));
        $templateProcessor->setValue('juristicAddress', $user->getJuristicAddress());
        $templateProcessor->setValue('countryCode', $user->getCountryCode());
        $templateProcessor->setValue('operatorCode', $user->getOperatorCode());
        $templateProcessor->setValue('phoneNumber', $user->getPhoneNumber());
        $templateProcessor->setValue('UNP', $user->getUNP());
        $templateProcessor->setValue('bankDetails', $user->getBankDetails());
        $templateProcessor->setValue('bankName', $user->getBankName());
        $templateProcessor->setValue('bankCode', $user->getBankCode());
        $templateProcessor->setValue('VAT', $this->container->getParameter('VAT'));


        $delivery = $order->getDelivery();
        $deliveries = $order->getDeliveries();
        $templateProcessor->setValue('delivery', $deliveries[$delivery]);

        if($delivery == 1){
            $templateProcessor->setValue('address', $order->getDeliveryAddress());
        }else{
            $address = $this->container->getParameter('manual_delivery_address');
            $templateProcessor->setValue('address', $address);
        }
        $templateProcessor->setValue('person', $user->getPerson());

        $templateProcessor->cloneRow('productNum', $order->getItems()->count());
        $productVATFullSum = 0;
        $productFullSum = 0;
        /**
         * @var OrderItem $item
         */
        foreach($order->getItems() as $index => $item) {

            if($delivery == 0){
                $cost = round($item->getProduct()->getCost() * 0.95);
                $productSum = $item->getQuantity() * $cost;
            }else{
                $cost = $item->getProduct()->getCost();
                $productSum = $item->getQuantity() * $item->getProduct()->getCost();
            }

            $productVATSum = $productSum * $this->container->getParameter('VAT') / 100 ;
            $productVATFullSum += $productVATSum;
            $productFullSum += $productSum;

            $templateProcessor->setValue(sprintf('productNum#%d', $index + 1), $index + 1);
            $templateProcessor->setValue(sprintf('productName#%d', $index + 1), htmlspecialchars($item->getProduct()->getName(), ENT_COMPAT, 'UTF-8'));
            $templateProcessor->setValue(sprintf('productOptionBox#%d', $index + 1), htmlspecialchars($item->getProduct()->getProductOptionBox(), ENT_COMPAT, 'UTF-8'));
            $templateProcessor->setValue(sprintf('productQuantity#%d', $index + 1), AmountExtension::staticFormat($item->getQuantity()));
            $templateProcessor->setValue(sprintf('productCost#%d', $index + 1), AmountExtension::staticFormat($cost));
            $templateProcessor->setValue(sprintf('sum#%d', $index + 1), AmountExtension::staticFormat($productSum));
            $templateProcessor->setValue(sprintf('productVAT#%d', $index + 1), $this->container->getParameter('vat'));
            $templateProcessor->setValue(sprintf('VATSUM#%d', $index + 1),AmountExtension::staticFormat(round($productVATSum)));
            $templateProcessor->setValue(sprintf('productSumWithVAT#%d', $index + 1),AmountExtension::staticFormat($productSum + round($productVATSum)));
        }

        $templateProcessor->setValue('fullSum',AmountExtension::staticFormat($productFullSum));
        $templateProcessor->setValue('VATFullSum',AmountExtension::staticFormat(round($productVATFullSum)));
        $templateProcessor->setValue('fullSumWithVAT',AmountExtension::staticFormat($productFullSum + round($productVATFullSum)));
        $templateProcessor->setValue('VATToString',AmountExtension::firstToUpper(Num2StrHelper::numToText(round($productVATFullSum))));
        $templateProcessor->setValue('sumToString',AmountExtension::firstToUpper(Num2StrHelper::numToText($productFullSum + round($productVATFullSum))));

        $newFileName = $user->getusername().'.docx';
        $newFilePath = $kernel->getRootDir().'/cache/'.$newFileName;
        $templateProcessor->saveAs($newFilePath);

        $converter = $this->get('meggi.converter.docx_to_pdf');
        $newFilePath = $converter->convert($newFilePath);

        $response = new BinaryFileResponse($newFilePath);
        $response->setContentDisposition('attachment', basename($newFilePath));

        return $response;
    }

    /**
     * @Route("/generate-agreement", name="order_generate_agreement")
     * @Template()
     */
    public function generateAgreementAction()
    {
        $user = $this->getUser();
        if(empty($user)){
            return $this->redirect($this->generateUrl('login_register_form'));
        }

        $kernel = $this->get('kernel');
        $path = $kernel->locateResource('@MeggiIndexBundle/Resources/templates/account.docx');
        $phpWord = new PhpWord();
        $templateProcessor = $phpWord->loadTemplate($path);
        $templateProcessor->setValue('docNum', AmountExtension::agreementFormat($user->getId()));
        $templateProcessor->setValue('companyName', $user->getCompanyName());
        $templateProcessor->setValue('firstName', $user->getFirstName());
        $templateProcessor->setValue('lastName', $user->getLastName());
        $templateProcessor->setValue('patronymic', $user->getPatronymic());
        $templateProcessor->setValue('position', $user->getPosition());
        $templateProcessor->setValue('date', $user->getCreatedAt()->format('d.m.Y'));
        $templateProcessor->setValue('juristicAddress', $user->getJuristicAddress());
        $templateProcessor->setValue('countryCode', $user->getCountryCode());
        $templateProcessor->setValue('operatorCode', $user->getOperatorCode());
        $templateProcessor->setValue('phoneNumber', $user->getPhoneNumber());
        $templateProcessor->setValue('unp', $user->getUNP());
        $templateProcessor->setValue('bankDetails', $user->getBankDetails());
        $templateProcessor->setValue('bankName', $user->getBankName());
        $templateProcessor->setValue('bankCode', $user->getBankCode());
        $templateProcessor->setValue('document', $user->getDocument());

        $newFileName = $user->getusername().'-agreement.docx';
        $newFilePath = $kernel->getRootDir().'/cache/'.$newFileName;
        $templateProcessor->saveAs($newFilePath);

        $converter = $this->get('meggi.converter.docx_to_pdf');
        $newFilePath = $converter->convert($newFilePath);

        $response = new BinaryFileResponse($newFilePath);
        $response->setContentDisposition('attachment', basename($newFilePath));

        return $response;

    }

    private function createApproveForm()
    {
        return $this->createFormBuilder()
            ->add('number', 'text')
            ->add('date', 'date', array(
                'years' => range(date('Y') , date('Y')+5),
            ))
            ->add('sum', 'text')
            ->add('order_id', 'hidden')
            ->setAction($this->generateUrl('approve_payment'))
            ->getForm()
            ;
    }
}
