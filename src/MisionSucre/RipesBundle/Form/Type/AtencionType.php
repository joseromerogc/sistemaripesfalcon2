<?php
namespace MisionSucre\RipesBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AtencionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        
        $builder->add('fecha','date', array('label' => 'Fecha de Atención','years'=>range(2010,2030)));
        $builder->add('cedula', 'text',array('label' => 'Cédula','mapped' => false));
        $builder->add('tipo', 'text',array('label' => 'Tipo de Atención'));
        $builder->add('observacion', 'textarea',array('label' => 'Observación'));
        $builder->add('idusr', 'hidden',array('mapped' => false));
        $builder-> add('estatus', 'choice', array(
        'choices' => array('Iniciado'=>'Iniciado', 'En Proceso'=>'En Proceso','Procesado'=>'Procesado','En Espera'=>'En Espera'),
            'placeholder'=>"Seleccione una",'label' => 'Estatus de la Atención'
            )); 
       
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MisionSucre\RipesBundle\Entity\Atencion'
        ));
    }

    public function getName()
    {
        return 'form';
    }
}
?>