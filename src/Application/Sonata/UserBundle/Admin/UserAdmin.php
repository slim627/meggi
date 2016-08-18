<?php

namespace Application\Sonata\UserBundle\Admin;

use FOS\UserBundle\Model\UserManagerInterface;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class UserAdmin extends Admin
{
    protected $userManager;

    /**
     * {@inheritdoc}
     */
    public function getExportFields()
    {
        // avoid security field to be exported
        return array_filter(parent::getExportFields(), function($v) {
            return !in_array($v, array('password', 'salt'));
        });
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('companyName')
            ->add('UNP')
            ->add('patronymic')
            ->add('position')
            ->add('juristicAddress')
            ->add('bankDetails')
            ->add('bankName')
            ->add('bankCode')
            ->add('person')
            ->add('countryCode')
            ->add('operatorCode')
            ->add('phoneNumber')
            ->add('document')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('email')
            ->add('companyName')
            ->add('position')
            ->add('juristicAddress')
            ->add('person')
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
            ->add('username', null, ['required' => true])
            ->add('plainPassword', 'text', array(
                'required' => (!$this->getSubject() || is_null($this->getSubject()->getId()))
            ))
            ->add('email', null, ['required' => true])
            ->add('companyName', null, ['required' => false])
            ->add('UNP', null, ['required' => false])
            ->add('patronymic', null, ['required' => false])
            ->add('position', null, ['required' => false])
            ->add('juristicAddress', null, ['required' => false])
            ->add('bankDetails', null, ['required' => false])
            ->add('bankName', null, ['required' => false])
            ->add('bankCode', null, ['required' => false])
            ->add('person', null, ['required' => false])
            ->add('countryCode', null, ['required' => false])
            ->add('operatorCode', null, ['required' => false])
            ->add('phoneNumber', null, ['required' => false])
            ->add('document', null, ['required' => false]);
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('username')
            ->add('email')
            ->add('companyName')
            ->add('UNP')
            ->add('patronymic')
            ->add('position')
            ->add('juristicAddress')
            ->add('bankDetails')
            ->add('bankName')
            ->add('bankCode')
            ->add('person')
            ->add('countryCode')
            ->add('operatorCode')
            ->add('phoneNumber')
            ->add('document');
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($user)
    {
        $userManager = $this->getConfigurationPool()->getContainer()->get('fos_user.user_manager');

        $userManager->updateCanonicalFields($user);

        $userManager->updatePassword($user);
    }
}
