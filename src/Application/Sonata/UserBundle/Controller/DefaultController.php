<?php

namespace Application\Sonata\UserBundle\Controller;

use Application\Sonata\UserBundle\Form\Type\RegistrationFormType;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class DefaultController extends Controller
{
    /**
     * @Route("/authorization", name="login_register_form")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        // Форма регистрации
        $regForm = $this->createForm( new RegistrationFormType($this->getDoctrine()->getManager()), new User() );
        $hideRegForm = false;
        if( $request->get($regForm->getName()) )
        {
            $regForm->handleRequest($request);
            if($regForm->isValid())
            {
                $user = $regForm->getData();

                $userManipulator = $this->get('fos_user.util.user_manipulator');

                $fosUser = $userManipulator->create($user->getEmail(), $user->getPlainPassword(), $user->getEmail(), true, false);

                if( $fosUser instanceof User )
                {
                    $fosUser->addRole('ROLE_USER');
                    $fosUser->setCompanyName($user->getCompanyName());
                    $fosUser->setUNP($user->getUNP());
                    $fosUser->setPatronymic($user->getPatronymic());
                    $fosUser->setPosition($user->getPosition());
                    $fosUser->setJuristicAddress($user->getJuristicAddress());
                    $fosUser->setBankDetails($user->getBankDetails());
                    $fosUser->setBankName($user->getBankName());
                    $fosUser->setBankCode($user->getBankCode());
                    $fosUser->setPerson($user->getPerson());
                    $fosUser->setCountryCode($user->getCountryCode());
                    $fosUser->setOperatorCode($user->getOperatorCode());
                    $fosUser->setPhoneNumber($user->getPhoneNumber());
                    $fosUser->setDocument($user->getDocument());
                    $fosUser->setFirstname($user->getFirstname());
                    $fosUser->setLastname($user->getLastname());

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($fosUser);
                    $em->flush($fosUser);
                }

                $loginManager = $this->get('fos_user.security.login_manager');
                $loginManager->loginUser('main', $fosUser);

                return $this->redirect($this->generateUrl('confirm'));
            }
        }

        // Форма авторизации
        $authForm = $this->createAuthForm($request);
        if( $request->get($authForm->getName()) )
        {
            $data = $authForm->getData();

            // Ищем пользователя и проверяем пароль
            $userManager = $this->get('fos_user.user_manager');
            $user = $userManager->findUserByEmail($data['email']);
            $factory = $this->get('security.encoder_factory');

            if( !$user || !$factory->getEncoder($user)->isPasswordValid($user->getPassword(), $data['password'], $user->getSalt()) )
            {
                $this->get('session')->getFlashBag()->add('notice', 'Неверный email или пароль');
            }
            else
            {
                $loginManager = $this->get('fos_user.security.login_manager');
                $loginManager->loginUser('main', $user);

                // Возвращаем пользователя на вызывающую страницу
                $returnUrl = ($request->get('returnUrl')) ? $request->get('returnUrl') : $request->headers->get('referer');
                return $this->redirect($returnUrl);
            }
        }

        return [
            'formRegView'   => $regForm->createView(),
            'formRegErrors' => $regForm->getErrors(),
            'hideRegForm'   => $hideRegForm,
            'authFormView'  => $authForm->createView(),
        ];
    }

    /**
     * @Route("/edit", name="profile_edit")
     * @Template()
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();
        if(!$user)
        {
            return $this->redirect($this->generateUrl('login_register_form'));
        }

        $form = $this->createForm( new RegistrationFormType($this->getDoctrine()->getManager(), false), $user );
        if( $request->get($form->getName()) )
        {
            $form->handleRequest($request);
            if($form->isValid())
            {
                $user = $form->getData();

                $userManager = $this->container->get('fos_user.user_manager');
                $userManager->updateUser($user, true);

                $userManager->refreshUser($user);
            }
        }

        return [
            'user' => $user,
            'formView' => $form->createView(),
        ];
    }

    private function createAuthForm(Request $request = null)
    {
        $data = ($request) ? $request->get('form') : null;

        return $this->createFormBuilder($data)
            ->add('email', 'email', ['required' => true, 'label' => false])
            ->add('password', 'password', ['required' => true, 'label' => false])
            ->getForm()
            ;
    }

    /**
     * @Route("/authorization-form", name="profile_authorization")
     * @Template()
     */
    public function authorizationFormAction()
    {
        $authForm = $this->createAuthForm();

        return [
            'authFormView' => $authForm->createView(),
        ];
    }

    /**
     * @Route("/registration-form", name="profile_registration")
     * @Template()
     */
    public function registrationFormAction(Request $request)
    {
        $form = $this->createForm( new RegistrationFormType($this->getDoctrine()->getManager()) );

        return [
            'formRegView' => $form->createView()
        ];
    }

    /**
     * @Route("/forgot-form", name="profile_forgot")
     * @Template()
     */
    public function forgotFormAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('email','text',['required' => true, 'attr' => ['placeholder' => 'Почта', 'class' => 'forgot_email']])
            ->getForm();

        $form->handleRequest($request);

        if($form->isValid()){
            $email = $form->getData()['email'];
            $user = $this->getDoctrine()
                ->getRepository('ApplicationSonataUserBundle:User')
                ->findOneBy(['email' => $email]);

            if ($user == NULL){
                $this->addFlash('forgot_error', 'user not found');
            }
            else {
                // генерация пароля
                $chars="qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP";
                $max=6;
                $size=strlen($chars)-1;
                $password=null;
                while($max--)
                    $password.=$chars[rand(0,$size)];
                // <---------------

                // изменения пароля на новый
                $userManager = $this->container->get('fos_user.user_manager');
                $user->setPlainPassword($password);
                $userManager->updateUser($user, true);
                // <------------------------

                // отправка email сообщения

                $emailSubject = $this->container->getParameter('forgot_email_subject');
                $emailSetFrom = $this->container->getParameter('forgot_email_from');;
                $emailContent = $this->container->getParameter('forgot_email_content');;

                $body =  str_replace('@password', $password, $emailContent);

                $mailer = $this->get('swiftmailer.mailer.default');
                $message = \Swift_Message::newInstance()
                    ->setSubject($emailSubject)
                    ->setFrom($emailSetFrom)
                    ->setTo($email)
                    ->setBody($body);
                $mailer->send($message);
                ;
                // <----------------------

                $this->addFlash('forgot_success', 'password regenerated');
            }

            return $this->redirectToRoute('index');
        }
        return ['form' => $form->createView()];
    }

    /**
     * @Route("/logout", name="profile_logout")
     * @Template()
     */
    public function logoutAction()
    {
        $this->get('security.context')->setToken(null);
        $this->get('request')->getSession()->invalidate();

        return $this->redirect($this->generateUrl('index'));
    }
}
