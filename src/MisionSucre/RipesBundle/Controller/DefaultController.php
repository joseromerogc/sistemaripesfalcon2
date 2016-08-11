<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function rootAction(Request $request)
    {   
        $em = $this->getDoctrine()->getManager();    
        
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        else
        {
            $user = $this->getUser();    
            
            $id= $user->getId();
            
            if($user->getTipUsr()==1)
            {   
                return $this->render('MisionSucreRipesBundle:Default:indexadmin.html.twig',array('role'=>'Administrador'));
            }
            
            if($user->getTipUsr()==2){
                return $this->render('MisionSucreRipesBundle:Default:indexadmin.html.twig',array('role'=>'Control de Estudio'));
            }
            
            $per = $em->getRepository('MisionSucreRipesBundle:Persona')->findOneByUser($id);
            
                if(!$per){
                    
                    $request->getSession()->getFlashBag()->add(
                    'notice',
                    'Datos Personales no registrados. Por Favor Completar los Datos'
                    );    
                    return $this->redirect($this->generateUrl('persona_new',array('id'=>$id)));
                }
            
        return $this->render('MisionSucreRipesBundle:Default:index.html.twig',array('per'=>$per));
        
        }
        
        
    }
}
