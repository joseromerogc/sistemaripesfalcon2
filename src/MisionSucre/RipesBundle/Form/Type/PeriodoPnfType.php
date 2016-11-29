<?php
namespace MisionSucre\RipesBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PeriodoPnfType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $valores=array();
        
                foreach(range(1,5) as $n)
                    $valores[$n]=$n;
                
        $builder-> add('trayecto', 'choice', array(
        'choices' => $valores,
            'placeholder'=>"Seleccione una",'label' => 'Trayecto'
            ));         
        $builder-> add('periodo', 'choice', array(
        'choices' => $valores,
            'placeholder'=>"Seleccione una",'label' => 'Periodo/Tramo'
            ));         
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MisionSucre\RipesBundle\Entity\PeriodoPnf'
        ));
    }

    public function getName()
    {
        return 'form';
    }
}
?>