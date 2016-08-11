<?php
namespace MisionSucre\RipesBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EstructuraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        
        $builder-> add('tipo', 'choice', array(
        'choices' => array('Cátedra BMC'=>'Cátedra BMC', 'Politíca'=>'Política','Estudiantil'=>'Estudiantil','UTM'=>'UTM'),
            'placeholder'=>"Seleccione una",'label' => 'Tipo'
            )); 
       
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MisionSucre\RipesBundle\Entity\Estructura'
        ));
    }

    public function getName()
    {
        return 'form';
    }
}
?>