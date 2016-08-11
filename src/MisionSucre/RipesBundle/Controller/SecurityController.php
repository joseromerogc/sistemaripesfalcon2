<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use MisionSucre\RipesBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {   

        $data = array();
    $form = $this->createFormBuilder($data)
        ->add('ca', 'captcha',  array( 'width' => 200,'label'=>'Autentificación Humana',
        'height' => 50,
        'length' => 6,
        'reload' => true,
            'as_url' =>true
            ))
        ->getForm();
    
    $form->handleRequest($request);
        
        if($this->getRequest()->isMethod('POST')){
        if ($form->isValid()){
             return $this->redirectToRoute('login_check', array(
    'request' => $request), 307); //GENIAL: REDIRECCIONA SIN PERDER EL POST
        }
        else
            {
             $request->getSession()->getFlashBag()->add(
            'notice',
            'Fallo en la Autentificación Humana'
            );
        }
        }
        
        $authenticationUtils = $this->get('security.authentication_utils');

    // get the login error if there is one
    $error = $authenticationUtils->getLastAuthenticationError();

    // last username entered by the user
    $lastUsername = $authenticationUtils->getLastUsername();
       
     if($error)
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Correo Electrónico o Contraseña Incorrecto'
            );  
        return $this->render('Security/login.html.twig',
            array(
                'last_username' => $lastUsername,
                'error' => $error,
                'form' => $form->createView()
            )
                );
    }
    
    public function checkAction(Request $request)
    {   
        
    }
    public function recuperarpasswordAction(Request $request)
    {   
        
        
    $data = array();
    $form = $this->createFormBuilder($data)
        ->add('email', 'email',  array('label'=>'Correo Electrónico'))
        ->getForm();
    
    $form->handleRequest($request);
    
    $error="";
    
        if($this->getRequest()->isMethod('POST')){
        
            
        if ($form->isValid()){
         
         //OBTENER INFORMACIÓN DE USUARIO DESDE SU EMAIL
        $em = $this->getDoctrine()->getManager();
        $email= $form->get('email')->getData();   
        
        $usr = $this->getDoctrine()
            ->getRepository('MisionSucreRipesBundle:User')
            ->findOneByUsername($email);
        
        if(!$usr){
            $request->getSession()->getFlashBag()->add(
            'notice',
            "Correo No Registrado"
            );
         return $this->redirect($this->generateUrl('recuperar_password'));
        }
        
        if($usr->getTipUsr()==1 || $usr->getTipUsr()==2 || $usr->getTipUsr()==5 || $usr->getTipUsr()==8){
         $request->getSession()->getFlashBag()->add(
            'notice',
            'Acción no Permitida'
            );
         return $this->redirect($this->generateUrl('login'));
            
        }
        
         $pass= $this->generarPassword();
         
         $usr->setPassword($pass);
         $encoder = $this->container->get('security.password_encoder');
         $encoded = $encoder->encodePassword($usr,  $usr->getPassword());
         $usr->setPassword($encoded);
         
         $em->persist($usr);
         $em->flush();
         
         $message = \Swift_Message::newInstance()
        ->setSubject('Recuperar Contraseña Sistema Ripes')
        ->setFrom("sistemaripesfalcon@gmail.com")
        ->setTo($email)
        ->setBody(
                $this->renderView(
                'Security/emailrecuperacion.html.twig',
                array('email' => $email,'pass'=>$pass,'id'=>$usr->getId())
            ),
            'text/html'
        )
         ;  
         
         if($this->get('mailer')->send($message)){
         $request->getSession()->getFlashBag()->add(
            'notice',
            'Contraseña Enviada con Éxito'
            );
         return $this->redirect($this->generateUrl('login'));
         }
         else{
         $request->getSession()->getFlashBag()->add(
            'notice',
            'Contraseña No Enviada'
            );
         }    
        }
        
         }
              
        return $this->render('Security/recuperarpassword.html.twig',
            array(
                'form' => $form->createView(),
                'error' =>$error
            )
                );
    }
    
    public function generarPassword(){
        
        $alpha = "abcdefghijklmnopqrstuvwxyz";
$alpha_upper = strtoupper($alpha);
$numeric = "0123456789";
$chars = "";

    // default [a-zA-Z0-9]{9}
    $chars = $alpha . $alpha_upper . $numeric;
    $length = 9;
 
$len = strlen($chars);
$pw = '';
 
for ($i=0;$i<$length;$i++)
        $pw .= substr($chars, rand(0, $len-1), 1);
 
// the finished password
$pw = str_shuffle($pw);

return $pw;
    }
    
}
