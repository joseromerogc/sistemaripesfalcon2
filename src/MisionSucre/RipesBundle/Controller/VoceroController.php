<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MisionSucre\RipesBundle\Entity\Vocero;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;

class VoceroController extends Controller
{
    public function indexAction()
    {
        return $this->render('MisionSucreRipesBundle:Usuario:index.html.twig');
    }
	
	public function newAction(Request $request,$idtrf)
	{       
		$vocero = new Vocero();
                
                $em = $this->getDoctrine()->getManager();
            
            $triunfador = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Triunfador')
                ->find($idtrf);
            
            $usertriunfador = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:User')
                ->find($triunfador->getUser()->getId());
            
            $ambiente = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Ambiente')
                ->find($triunfador->getAmbiente()->getId());
            
            $voceroambiente= $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Vocero')
                ->findByAmbiente($ambiente->getId());
            
            $idaldea = $ambiente->getAldea()->getId();
            
            $aldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Aldea')
                ->find($idaldea);
                
            if($voceroambiente){

                $request->getSession()->getFlashBag()->add(
                'notice',
                "Vocero ya registro Vocero"
                );
                return $this->redirect($this->generateUrl('ambiente_show',array('idamb'=>$ambiente->getId())));
                }
            
            
            if(!$triunfador){

                $request->getSession()->getFlashBag()->add(
                'notice',
                "Triunfador con ID $idtrf no Registrado"
                );
                return $this->redirect($this->generateUrl('ambiente_show',array('idamb'=>$ambiente->getId())));
                }
            
                $usr = $this->getUser();
                if($usr->getTipUsr()=='5')
                {
                $caldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                ->findCoordinadorAldeaByUserAndId($idaldea,$usr->getId());
                
                if(!$caldea){

                $request->getSession()->getFlashBag()->add(
                'notice',
                'Usted no es Coordinador de Esta Aldea'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                }
                
                if(!$ambiente){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ambiente con ID: '.$idambiente.' no registrado'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                
                if(!$usertriunfador){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Usuario con ID: '.$idtrf.' no registrado'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
               
                if(!$triunfador){

                $request->getSession()->getFlashBag()->add(
                'notice',
                "Triunfador con ID $idtrf no Registrado"
                );
                return $this->redirect($this->generateUrl('ambiente_show',array('idamb'=>$ambiente->getId())));
                }
                            $vocero->setUser($usertriunfador);    
                            $vocero->setAmbiente($ambiente);    
                            $em->persist($vocero);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Vocero Creado con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('ambiente_show',array('idamb'=>$ambiente->getId())));   
	}
        
        public function updateAction(Request $request,$idtrf)
	{       
            
            $em = $this->getDoctrine()->getManager();
            
            $triunfador = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Triunfador')
                ->find($idtrf);
            
            $usertriunfador = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:User')
                ->find($triunfador->getUser()->getId());
            
            $ambiente = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Ambiente')
                ->find($triunfador->getAmbiente()->getId());
            
            $vocero= $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Vocero')
                ->findOneByAmbiente($ambiente->getId());
            
            if(!$vocero){

                $request->getSession()->getFlashBag()->add(
                'notice',
                "Ambiente no tiene Vocero"
                );
                return $this->redirect($this->generateUrl('ambiente_show',array('idamb'=>$ambiente->getId())));
                }
            
            if(!$triunfador){

                $request->getSession()->getFlashBag()->add(
                'notice',
                "Triunfador con ID $idtrf no Registrado"
                );
                return $this->redirect($this->generateUrl('ambiente_show',array('idamb'=>$ambiente->getId())));
                }
            
                $usr = $this->getUser();
                if($usr->getTipUsr()=='5')
                {
                $caldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                ->findCoordinadorAldeaByUserAndId($idaldea,$usr->getId());
                
                if(!$caldea){

                $request->getSession()->getFlashBag()->add(
                'notice',
                'Usted no es Coordinador de Esta Aldea'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                }
                $idaldea = $ambiente->getAldea()->getId();
                $aldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Aldea')
                ->find($idaldea);
                
                if(!$ambiente){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ambiente con ID: '.$idambiente.' no registrado'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                
                if(!$usertriunfador){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Usuario con ID: '.$idtrf.' no registrado'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                
                if(!$triunfador){

                $request->getSession()->getFlashBag()->add(
                'notice',
                "Triunfador con ID $idtrf no Registrado"
                );
                return $this->redirect($this->generateUrl('ambiente_show',array('idamb'=>$ambiente->getId())));
                }
                
                
                            $vocero->setUser($usertriunfador);    
                            $vocero->setAmbiente($ambiente);    
                            $em->persist($vocero);
                            $em->flush();
                            
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Vocero Actualizado con Éxito'
                            );            
                    return $this->redirect($this->generateUrl('ambiente_show',array('idamb'=>$ambiente->getId())));   
	}
        
    public function asignarvoceroAction($idamb)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $ambiente = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Ambiente')
                ->find($idamb);
                
                
                if(!$ambiente){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ambiente con ID: '.$idaldea.' no registrado'
                );
                return $this->redirect($this->generateUrl('ambiente'));
                }
                
                $idaldea = $ambiente->getAldea()->getId();
                
                $aldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Aldea')
                ->find($idaldea);
                
                $usr = $this->getUser();
                if($usr->getTipUsr()=='5')
                {
                $caldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                ->findCoordinadorAldeaByUserAndId($idaldea,$usr->getId());
                
                if(!$caldea){

                $request->getSession()->getFlashBag()->add(
                'notice',
                'Usted no es Coordinador de Esta Aldea'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                }
                
                $triunfadores = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Triunfador')
                ->findAllOrderedByAmbiente($idamb);
                
		return $this->render(
			'MisionSucreRipesBundle:Vocero:seleccionarvocero.html.twig',
                        array('aldea'=>$aldea,'ambiente'=>$ambiente,'triunfadores'=>$triunfadores)
		);	
	}
    
    public function asignarvoceroupdateAction($idamb)
	{       
                $em = $this->getDoctrine()->getManager();
                
                $ambiente = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Ambiente')
                ->find($idamb);
                
                
                if(!$ambiente){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ambiente con ID: '.$idaldea.' no registrado'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                
                $idaldea = $ambiente->getAldea()->getId();
                
                $aldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Aldea')
                ->find($idaldea);
                
                $usr = $this->getUser();
                if($usr->getTipUsr()=='5')
                {
                $caldea = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:CoordinadorAldea')
                ->findCoordinadorAldeaByUserAndId($idaldea,$usr->getId());
                
                if(!$caldea){

                $request->getSession()->getFlashBag()->add(
                'notice',
                'Usted no es Coordinador de Esta Aldea'
                );
                return $this->redirect($this->generateUrl('usuario_show',array('id'=>$usr->getId())));
                }
                }
                
                $triunfadores = $this->getDoctrine()
                ->getRepository('MisionSucreRipesBundle:Triunfador')
                ->findAllOrderedByAmbiente($idamb);
                
		return $this->render(
			'MisionSucreRipesBundle:Vocero:seleccionarvoceroupdate.html.twig',
                        array('aldea'=>$aldea,'ambiente'=>$ambiente,'triunfadores'=>$triunfadores)
		);	
	}    
    public function listaAction(Request $request)
	{   
    
            $em = $this->getDoctrine()->getManager();
            
            $user = $this->getUser();
        
        switch($user->getTipUsr()){
        
        case 5:
            $coordinador = $em->getRepository('MisionSucreRipesBundle:CoordinadorAldea')->findOneByUser($user->getId());
                        if(!$coordinador){
                            $request->getSession()->getFlashBag()->add(
                            'notice',
                            'Coordinador No Vinculado a una Aldea'
                            );            
                            return $this->redirect($this->generateUrl('aldea_coordinador_info'));
                        }
            $voceros = $em->getRepository('MisionSucreRipesBundle:Voceros')->findAllOrderedByAldea($coordinador->getAldea()->getId());
            break;
        case 8:    
        
        $usreje = $em->getRepository('MisionSucreRipesBundle:CoordinadorEje')->findOneByUser($user->getId());
        $voceros = $em->getRepository('MisionSucreRipesBundle:Triunfador')->findAllOrderedByEgresadoAndEje($usreje->getEje()->getId());
        break;
        case 1:
        $voceros = $em->getRepository('MisionSucreRipesBundle:Vocero')->findAllOrderedByPersona();
        break;
        }
               
		if(!$voceros){
                
            $request->getSession()->getFlashBag()->add(
            'notice',
            'Ningun Vocero Registrado'
            );
            return $this->redirect($this->generateUrl('ambiente'));
            }
            
		return $this->render(
			'MisionSucreRipesBundle:Vocero:lista.html.twig',
                array('voceros' => $voceros)
		);	
}    
}
