<?php
namespace MisionSucre\RipesBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AmbienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $proms=array();
        
                foreach(range(1,50) as $n)
                    $proms[$n]=$n;
        
        $builder-> add('vinculadosistema', 'choice', array(
        'choices' => array('Si'=>'Si', 'No'=>'No'),
            'placeholder'=>"Seleccione una",'label' => 'Vinculado Al Sistema Sucre'
            )); 
        $builder->add('modalidad', 'choice', array(
        'choices' => array('TRIMESTRAL'=>'TRIMESTRAL','SEMESTRAL'=>'SEMESTRAL','TI'=>'TI' ),'mapped' =>false,'required' =>false,
            'placeholder'=>"Seleccione una",'label' => 'Modalidad del P.N.F.'
            ))->         
        add('promocion', 'choice', array(
        'choices' => $proms,
            'placeholder'=>"Seleccione una",'label' => 'Promoción','required' =>false
            ))                
             ->add('nombre', 'text', array('label' => 'Nombre de Ambiente(*OPCIONAL)','required' =>false
            ))->              
        add('condicion', 'choice', array(
        'choices' => array('Nuevo'=>'Nuevo','Activo'=>'Activo','Egresado'=>'Egresado','Abandonado'=>'Abandonado','Culminado'=>'Culminado' ),
            'placeholder'=>"Seleccione una",'label' => 'Condición del Ambiente'
        ))    
        ->add('tegreso', 'hidden',array('mapped' => false))                       
        ->add('tingreso', 'hidden',array('mapped' => false))
        ->add('idpnf', 'hidden',array('mapped' => false));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MisionSucre\RipesBundle\Entity\Ambiente'
        ));
    }

    public function getName()
    {
        return 'form';
    }
}
?>