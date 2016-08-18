<?php

namespace Meggi\IndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     * @Template()
     */
    public function indexAction()
    {
        $banners = $this->getDoctrine()->getRepository('MeggiIndexBundle:Banner')->findby([],['weight' => 'DESC']);

        return [
            'banners' => $banners,
        ];
    }

    /**
     * @Template()
     */
    public function copyrightAction()
    {
        $copyright = $this->getDoctrine()->getRepository('MeggiIndexBundle:Config')->getOwnConfig('COPYRIGHT');

        return [
            'copyright' => strip_tags($copyright->getValue())
        ];
    }

    /**
     * @Template()
     */
    public function linksAction()
    {
        $server = $this->get('request')->server;
        $uri = $server->get('REDIRECT_URL');
        return [
            'uri' => $uri,
        ];
    }

    /**
     * @Template()
     */
    public function brandsAction()
    {
        $brands = $this->getDoctrine()->getRepository('MeggiIndexBundle:Brand')->findBy([],['weight' => 'DESC']);
        $server = $this->get('request')->server;
        $uri = $server->get('REDIRECT_URL');

        return [
            'brands' => $brands,
            'uri' => $uri,
        ];
    }

    /**
     * @Route("/company", name="company")
     * @Template()
     */
    public function companyAction()
    {
        $company = $this->getDoctrine()->getRepository('MeggiIndexBundle:Config')->findOneBy(['keyValue' => 'COMPANY']);

        return [
            'company' => $company,
        ];
    }

    /**
     * @Route("/where-buy", name="where_buy")
     * @Template()
     */
    public function whereByAction()
    {
        $whereBuy = $this->getDoctrine()->getRepository('MeggiIndexBundle:Config')->findOneBy(['keyValue' => 'WHERE_BUY']);

        return [
            'whereBuy' => $whereBuy,
        ];
    }

    /**
     * @Route("/partners", name="partners")
     * @Template()
     */
    public function partnersAction()
    {
        $partners = $this->getDoctrine()->getRepository('MeggiIndexBundle:Config')->findOneBy(['keyValue' => 'PARTNERS']);

        return [
            'partners' => $partners,
        ];
    }

    /**
     * @Template()
     */
    public function loginAction()
    {
        return [
            'user' => $this->getUser(),
        ];
    }


}
