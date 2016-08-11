<?php
namespace MisionSucre\RipesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ParticipacionPoliticaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $nums=array();
        
        foreach(range(1,12) as $n)
             $nums[$n]=$n;   
        
        $builder->add('cargo', 'text',array('label' => 'Cargo'));
        $builder->add('afiliacion', 'choice', array(
        'choices' => array('PSUV'=>'PSUV','PSUV'=>'PSUV','GPP'=>'GPP','Otro'=>'Otro'),'placeholder' => 'Seleccione',
            'label' => 'Afiliación Politica'));
        $builder->add('ubch', 'hidden',array('mapped' => false));
        $builder->add('mesa', 'choice', array(
        'choices' => $nums,'label' => 'Mesa de Votación','required'=>false));
        
        $builder->add('save', 'submit',array('label' => 'Registrar'));
        
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MisionSucre\RipesBundle\Entity\ParticipacionPolitica'
        ));
    }

    public function getName()
    {
        return 'politica';
    }
}
?>