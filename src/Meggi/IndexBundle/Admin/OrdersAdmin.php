<?php

namespace Meggi\IndexBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class OrdersAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('user')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('user')
            ->add('updatedAt')
            ->add('delivery', 'string', ['template' => 'MeggiIndexBundle:Preview:preview.order.delivery.list.html.twig'])
            ->add('status', 'string', ['template' => 'MeggiIndexBundle:Preview:preview.order.status.list.html.twig'])
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
        $object = $this->getSubject();
        $statuses = $this->getSubject()->getStatuses();

        $formMapper
            ->with('Заказ №'.$object->getId())
            ->add('status', 'choice', ['choices' => $statuses])
            ->add('file','itm_file_preview', ['required' => false, 'data_class' => null])
            ->end()
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $object = $this->getSubject();

        $showMapper
            ->with('Заказ №'.$object->getId())
            ->add('id')
            ->add('user')
            ->add('message')
            ->add('payment_number')
            ->add('payment_date', 'string', ['template' => 'MeggiIndexBundle:Preview:preview.order.payment_date.show.html.twig'])
            ->add('payment_sum')
            ->add('status', 'string', ['template' => 'MeggiIndexBundle:Preview:preview.order.status.show.html.twig'])
            ->add('delivery', 'string', ['template' => 'MeggiIndexBundle:Preview:preview.order.delivery.show.html.twig'])
            ->add('item', 'string', ['template' => 'MeggiIndexBundle:Preview:preview.order.product.show.html.twig'])
            ->end()
        ;
    }
}
