<?php

namespace MisionSucre\RipesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class GuiaController extends Controller
{
    
    public function indexAction(Request $request)
	{       
        
        return $this->render('MisionSucreRipesBundle:Guia:index.html.twig', 
                array()
                );
        
        }
        
        //INGRESO
    public function ingresoAction(Request $request)
	{       
        
        return $this->render('MisionSucreRipesBundle:Guia:ingreso.html.twig', 
                array()
                );
        
        }
    public function ingresorecuperarpassAction(Request $request)
	{       
        
        return $this->render('MisionSucreRipesBundle:Guia:recuperarpass.html.twig', 
                array()
                );
        
        }
        
    public function cambiarpassAction(Request $request)
	{       
        
        return $this->render('MisionSucreRipesBundle:Guia:cambiarpass.html.twig', 
                array()
                );
        
        }
        
    public function usuario_nuevoAction(Request $request)
	{       
        
        return $this->render('MisionSucreRipesBundle:Guia:usuarionuevo.html.twig', 
                array()
                );
        
        }
    public function usuario_cargaAction(Request $request)
	{       
        
        return $this->render('MisionSucreRipesBundle:Guia:cargausuario.html.twig', 
                array()
                );
        
        }
    

        }
