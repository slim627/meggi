<?php

namespace Meggi\IndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NewsController extends Controller
{
    /**
     * @Route("/news" , name="news")
     * @Template()
     */
    public function newsAction()
    {
        $news = $this->getDoctrine()
            ->getRepository('MeggiIndexBundle:News')
            ->findBy(['isShare' => false],['createdAt' => 'DESC']);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $news,
            $this->get('request')->get('page', 1), 6
        );

        if(!$news)
        {
            throw new NotFoundHttpException();

        }

        return array('pagination' => $pagination, 'isShare' => false); /*all news*/
    }

    /**
     * @Route("/shares" , name="shares")
     * @Template(template="@MeggiIndex/News/news.html.twig")
     */
    public function sharesAction()
    {
        $news = $this->getDoctrine()
            ->getRepository('MeggiIndexBundle:News')
            ->findBy(['isShare' => true],['createdAt' => 'DESC']);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $news,
            $this->get('request')->get('page', 1), 6
        );

        if(!$news)
        {
            throw new NotFoundHttpException();

        }

        return array('pagination' => $pagination, 'isShare' => true); /*all shares*/
    }

    /**
     * @Route("/news/{url}" , name="news_name")
     * @Template()
     */
    public function oneNewsAction($url)
    {

        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();

        $query = $qb->select('news')
            ->from('MeggiIndexBundle:News', 'news')
            ->andWhere('news.url = ?1')
            ->andWhere('news.isShare = 0')
            ->setParameters(array(1 => $url));

        $news = $query->getQuery()->getOneOrNullResult();

        if(!$news)
        {
            throw new NotFoundHttpException();
        }

        return ['news' => $news, 'isShare' => false]; /*one news*/
    }

    /**
     * @Route("/share/{url}" , name="shares_name")
     * @Template(template="@MeggiIndex/News/oneNews.html.twig")
     */
    public function oneShareAction($url)
    {
        $qb = $this->getDoctrine()->getManager()->createQueryBuilder();

        $query = $qb->select('news')
            ->from('MeggiIndexBundle:News', 'news')
            ->andWhere('news.url = ?1')
            ->andWhere('news.isShare = 1')
            ->setParameters(array(1 => $url));

        $news = $query->getQuery()->getOneOrNullResult();

        if(!$news)
        {
            throw new NotFoundHttpException();
        }

        return ['news' => $news, 'isShare' => true]; /*one share*/
    }
}
