<?php
namespace MisionSucre\RipesBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UbicacionViviendaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $builder->add('sector', 'hidden',array('mapped' => false));
        $builder->add('direccion', 'textarea',array('label' => 'Dirrección de Habitación','required'=>'false'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MisionSucre\RipesBundle\Entity\UbicacionVivienda'
        ));
    }

    public function getName()
    {
        return 'ubicacionvivienda';
    }
}
?>