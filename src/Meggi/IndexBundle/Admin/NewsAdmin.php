<?php
/**
 * Created by PhpStorm.
 * User: archer.developer
 * Date: 24.07.14
 * Time: 22:09
 */

namespace Meggi\IndexBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class NewsAdmin extends Admin
{
    /**
     * @param \Sonata\AdminBundle\Show\ShowMapper $showMapper
     *
     * @return void
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('name')
            ->add('createdAt', 'string', ['template' => 'MeggiIndexBundle:Preview:date.show.html.twig'])
            ->add('content', 'string', array('template' => 'MeggiIndexBundle:Preview:tags.news-content.show.html.twig'))
            ->add('isShare')
//            ->add('metaTitle')
//            ->add('metaDescription')
//            ->add('metaKeywords')
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     *
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $news = $this->getSubject();

        $formMapper
            ->add('name')
            ->add('isShare');

        if($news->getCreatedAt() != null ){
            $formMapper
                ->add('createdAt', 'sonata_type_date_picker', array('dp_side_by_side' => true, 'format'=>'dd-MM-yyyy'));
        }

        $formMapper
            ->add('content', 'ckeditor',array(
                'config_name' => 'default',
            ))

            ->end()
//			->with('SEO', array('description' => 'Данные для SEO'))
//                ->add('metaTitle', null, array('required' => false))
//                ->add('metaDescription', null, array('required' => false))
//                ->add('metaKeywords', null, array('required' => false))
//            ->end()
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     *
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add('isShare', null, ['editable' => true])
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )))
        ;
    }

    /**
     * @param \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     *
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('content')
        ;
    }

    public function prePersist($object)
    {
        if($object->getMetaTitle() == NULL){

            $object->setMetaTitle($object->getName());

        }
    }

    public function preUpdate($object)
    {
        if($object->getMetaTitle() == NULL){

            $object->setMetaTitle($object->getName());

        }
    }
} 