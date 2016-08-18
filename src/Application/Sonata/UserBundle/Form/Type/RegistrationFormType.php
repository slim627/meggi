<?php
/**
 * Created by PhpStorm.
 * User: archer.developer
 * Date: 31.08.14
 * Time: 20:08
 */

namespace Application\Sonata\UserBundle\Form\Type;

use Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class RegistrationFormType extends AbstractType
{
    public function __construct($em, $passwordRequired = true)
    {
        $this->em = $em;
        $this->passwordRequired = $passwordRequired;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('companyName', null, ['required' => true, 'label' => false, 'attr' => ['placeholder' => 'Название организации']])
            ->add('UNP', null, ['required' => true, 'label' => false, 'attr' => ['placeholder' => 'УНП']])
            ->add('firstname', null, array('required' => true, 'label' => false, 'attr' => ['placeholder' => 'Имя']))
            ->add('lastname', null, array('required' => true, 'label' => false, 'attr' => ['placeholder' => 'Фамилия']))
            ->add('patronymic', null, array('required' => true, 'label' => false, 'attr' => ['placeholder' => 'Отчество']))
            ->add('position', null, array('required' => true, 'label' => false, 'attr' => ['placeholder' => 'Должность']))
            ->add('document', null, array('required' => true, 'label' => false, 'attr' => ['placeholder' => 'Документ на основании, которого действует лицо']))
            ->add('juristicAddress', null, array('required' => true, 'label' => false, 'attr' => ['placeholder' => 'Адрес']))
            ->add('bankDetails', null, array('required' => true, 'label' => false, 'attr' => ['placeholder' => 'Расчетный счет']))
            ->add('bankName', null, array('required' => true, 'label' => false, 'attr' => ['placeholder' => 'Наименование банка']))
            ->add('bankCode', null, array('required' => true, 'label' => false, 'attr' => ['placeholder' => 'Код банка']))
            ->add('person', null, array('required' => true, 'label' => false, 'attr' => ['placeholder' => 'ФИО']))
            ->add('countryCode', null, array('required' => true, 'label' => false))
            ->add('operatorCode', null, array('required' => true, 'label' => false))
            ->add('phoneNumber', null, array('required' => true, 'label' => false))
            ->add('email', 'email', array('required' => true, 'label' => false, 'attr' => ['placeholder' => 'E-mail']))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'required' => $this->passwordRequired,
                'first_options' => array('label' => false,'attr' => ['placeholder' => 'Пароль']),
                'second_options' => array('label' => false,'attr' => ['placeholder' => 'Повторите пароль']),
                'invalid_message' => 'Пароли не совпадают',
            ))
            ->addEventListener(FormEvents::BIND, function(FormEvent $event){
                $data = $event->getData();

                if (!$data instanceof User) {
                    return;
                }
                $data->setUsername($data->getEmail());
            })
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\Sonata\UserBundle\Entity\User',
            'intention'  => 'registration',
            'validation_groups' => array('Registration'),
        ));
    }

    public function getName()
    {
        return 'application_user_registration';
    }
}