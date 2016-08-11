<?php
namespace MisionSucre\RipesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DiscapacidadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        
        
        $builder->add('nombre','choice',
                array('choices' => array('Deficiencia Motriz'=>'Deficiencia Motriz',
                    'Visual'=>'Visual','Auditiva'=>'Auditiva',
            'Psíquica'=>'Psíquica','Intelectual'=>'Intelectual'),
            'label' => 'Discapacidad','placeholder'=>'Seleccione'
            ));
        $builder->add('save', 'submit',array('label' => 'Registrar'));
        
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MisionSucre\RipesBundle\Entity\Discapacidad'
        ));
    }

    public function getName()
    {
        return 'discapacidad';
    }
}
?>