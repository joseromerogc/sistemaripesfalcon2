<?php
namespace MisionSucre\RipesBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConsejoComunalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $builder->add('prq', 'hidden',array('mapped' => false));
        $builder->add('nombre', 'text',array('label' => 'Nombre del Consejo Comunal*'));
        $builder->add('direccion', 'textarea',array('label' => 'Dirrección de Habitación','required'=>'false'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MisionSucre\RipesBundle\Entity\ConsejoComunal'
        ));
    }

    public function getName()
    {
        return 'consejo_comunal';
    }
}
?>