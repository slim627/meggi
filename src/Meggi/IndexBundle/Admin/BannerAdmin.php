<?php

namespace Meggi\IndexBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Meggi\IndexBundle\Entity\Banner;


class BannerAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('position')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('title', 'string', ['template' => 'MeggiIndexBundle:Preview:banner.title.list.html.twig'])
            ->add('weight')
            ->add('picture', 'string', ['template' => 'MeggiIndexBundle:Preview:banner.list.html.twig'])
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', 'ckeditor',array(
                'config_name' => 'default',
            ))
            ->add('weight')
            ->add('description', 'ckeditor',array(
                'config_name' => 'default',
                'required'    => false
            ))
            ->add('link', null, ['required' => false])
            ->add('color')
            ->add('picture','itm_image_preview', ['required' => false, 'data_class' => null])
            ->add('position','choice', ['choices' => Banner::getPositions()])
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('title', null, array('safe' => true))
            ->add('weight')
            ->add('description', null, array('safe' => true))
            ->add('link')
            ->add('color')
            ->add('picture', 'string', ['template' => 'MeggiIndexBundle:Preview:banner.show.html.twig'])
            ->add('position')
        ;
    }
}
