<?php
namespace MisionSucre\RipesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ParticipacionComunitariaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        
        $builder->add('voceria', 'text',array('label' => 'Voceria'));
        $builder->add('organizacion_social', 'text',array('label' => 'Organizacion Social'));
        $builder->add('save', 'submit',array('label' => 'Registrar'));
        
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MisionSucre\RipesBundle\Entity\ParticipacionComunitaria'
        ));
    }

    public function getName()
    {
        return 'participacion_comunitaria';
    }
}
?>