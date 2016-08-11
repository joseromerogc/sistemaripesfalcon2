<?php
namespace MisionSucre\RipesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TrabajoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        
        $turno=array('Diurno'=>'Diurno','Nocturno'=>'Nocturno'
                    );
        ksort($turno);
        $builder->add('cargo', 'text',array('label' => 'Cargo'));
        $builder->add('institucion', 'text',array('label' => 'Institución'));
        $builder->add('antiguedad', 'text',array('label' => 'Antiguedad'));
        $builder->add('jubilacion', 'text',array('label' => 'Jubilación'));
        $builder->add('condicionlaboral', 'text',array('label' => 'Condición Laboral'));
        $builder->add('turno','choice',
                array('choices' => $turno,
            'label' => 'Turno','placeholder'=>'Seleccione'
            ));
        $builder->add('save', 'submit',array('label' => 'Registrar'));
        
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MisionSucre\RipesBundle\Entity\Trabajo'
        ));
    }

    public function getName()
    {
        return 'trabajo';
    }
}
?>