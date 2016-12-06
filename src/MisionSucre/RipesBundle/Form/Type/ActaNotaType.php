<?php
namespace MisionSucre\RipesBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ActaNotaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        
        $builder->add('cedula', 'text',array('label' => 'Cédula de Docente','mapped' => false));
        $builder->add('iddoc', 'hidden',array('mapped' => false));
        
       
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MisionSucre\RipesBundle\Entity\ActaNota'
        ));
    }

    public function getName()
    {
        return 'form';
    }
}
?>