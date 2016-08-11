<?php
namespace MisionSucre\RipesBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MiembroEstructuraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        
        $builder->add('cargo', 'text',array('label' => 'Cargo'));
        $builder->add('cedula', 'text',array('label' => 'Cédula','mapped' => false));
        $builder->add('idusr', 'hidden',array('mapped' => false));
       
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MisionSucre\RipesBundle\Entity\MiembroEstructura'
        ));
    }

    public function getName()
    {
        return 'form';
    }
}
?>