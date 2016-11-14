<?php
namespace MisionSucre\RipesBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UnidadCurricularType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $builder->
        add('nombre', 'text', array('label' => 'Nombre de la Unidad Curricular'))->
        add('area', 'text', array('label' => 'Área de la Unidad Curricular'))->
        add('codigo', 'text', array('label' => 'Código de la Unidad Curricular(*Único)'))
        ->add('idpnf', 'hidden',array('mapped' => false));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MisionSucre\RipesBundle\Entity\UnidadCurricular'
        ));
    }

    public function getName()
    {
        return 'form';
    }
}
?>