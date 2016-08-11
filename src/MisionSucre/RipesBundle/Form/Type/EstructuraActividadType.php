<?php
namespace MisionSucre\RipesBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EstructuraActividadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        
         $builder->add('objetivo', 'text',array('label' => 'Objetivo de la Actividad(opcional)'));
         $builder->add('nombre', 'text',array('label' => 'Nombre de la Actividad'));
         $builder->add('observacion', 'text',array('label' => 'Observación(opcional)','required'=>false));
         $builder->add('lugar', 'textarea',array('label' => 'Lugar de Realización'));
         $builder->add('fecha','date', array('label' => 'Fecha de Realización','years'=>range(2016,2030)));
       $builder-> add('cumplimiento', 'choice', array(
        'choices' => array('Si'=>'Si', 'No'=>'No'),
            'placeholder'=>"Seleccione una",'label' => 'Cumplimiento'
            )); 
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MisionSucre\RipesBundle\Entity\EstructuraActividad'
        ));
    }

    public function getName()
    {
        return 'actividad';
    }
}
?>