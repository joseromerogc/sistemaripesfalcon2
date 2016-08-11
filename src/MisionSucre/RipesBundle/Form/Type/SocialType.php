<?php
namespace MisionSucre\RipesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SocialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $builder->add('cant_hij', 'number',array('max_length'=>'2','label' => 'Cantidad de Hijos'));
        $builder->add('ing_fam', 'choice', array(
        'choices' => array('Menos de 5000'=>'Menos de 5000',
            '5000-10000'=>'5000-10000','10000-15000'
            ,'Más de 15000'=>'Más de 15000'),
            'label' => 'Ingreso Familiar','placeholder'=>'Seleccione'
            ));
        $builder->add('tipo_viv', 'choice', array(
        'choices' => array('Propia'=>'Propia','Alquilada'=>'Alquilada','Al cuido'=>'Al Cuido','Otro'=>'Otro'),'label' => 'Tipo de Vivienda','placeholder'=>'Seleccione'
            ));
        $builder->add('religion', 'choice', array(
        'choices' => array('Católica'=>'Católica','Evangélica'=>'Evangelica','judia'=>'Judía','Agnóstica'=>'Agnóstica','Ninguna'=>'Ninguna'),'label' => 'Religión','placeholder'=>'Seleccione'
            ));
       $builder->add('ayuda', 'choice', array(
        'choices' => array('Si'=>'Si','No'=>'No'),'label' => 'Recibe Beca o Ayuda del Gobierno','placeholder'=>'Seleccione'
            ));
        $builder->add('save', 'submit',array('label' => 'Registrar'));
        
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MisionSucre\RipesBundle\Entity\Social'
        ));
    }

    public function getName()
    {
        return 'social';
    }
}
?>