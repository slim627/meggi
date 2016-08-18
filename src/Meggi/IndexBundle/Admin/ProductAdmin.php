<?php

namespace Meggi\IndexBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ProductAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('barCode')
            ->add('cost')
            ->add('isAvailable')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add('categories')
            ->add('cost')
            ->add('isAvailable')
            ->add('picture', 'string',['template' =>'MeggiIndexBundle:Preview:product.list.html.twig'])
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
            ->add('name')
            ->add('isAvailable', null, ['required' => false])
            ->add('brand')
            ->add('categories')
            ->add('productOption','sonata_type_model')
            ->add('productOptionBox','sonata_type_model', ['required' => false])
            ->add('quantityForOne')
            ->add('description', 'ckeditor',array(
                'config_name' => 'default',
                'required'    => false
            ))
            ->add('article')
            ->add('barCode')
            ->add('quantity')
            ->add('cost')
            ->add('quantityCost', 'hidden', ['required' => false])
            ->add('picture','itm_image_preview', ['required' => false, 'data_class' => null])
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('isAvailable')
            ->add('brand')
            ->add('categories')
            ->add('productOption')
            ->add('productOptionBox')
            ->add('quantityForOne')
            ->add('description', null, array('safe' => true))
            ->add('article')
            ->add('barCode')
            ->add('quantity')
            ->add('cost')
            ->add('quantityCost')
            ->add('picture', 'string',['template' =>'MeggiIndexBundle:Preview:product.show.html.twig'])
        ;
    }

    public function prePersist($object)
    {
        $pattern = '/[^0-9]/';
        $cost = preg_replace($pattern, "", $object->getCost());
        $quantity = preg_replace($pattern, "", $object->getQuantity());
        $object->setQuantityCost(intval($quantity) * intval($cost));


    }

    public function preUpdate($object)
    {
        $pattern = '/[^0-9]/';
        $cost = preg_replace($pattern, "", $object->getCost());
        $quantity = preg_replace($pattern, "", $object->getQuantity());
        $object->setQuantityCost(intval($quantity) * intval($cost));
    }
}
