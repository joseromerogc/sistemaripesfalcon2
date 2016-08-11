<?php
namespace MisionSucre\RipesBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AcademicoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $bachillerato=array('Institución Pública'=>'Institución Pública','Institución Privada'=>'Institución Privada',
            'Misión Ribas'=>'Misión Ribas','Libre Escolaridad'=>'Libre Escolaridad'
            );
        ksort($bachillerato);
        
        $builder->add('bachillerato', 'choice', array(
        'choices' => $bachillerato,'label' => 'Modalidad de Estudio Bachillerato', 'placeholder'=>'Seleccione una'
            ));
         $builder->add('titulouniversitario', 'text',array(
            'label' => 'Título Universitario'
            )
            );
        $builder->add('postgrado', 'text', array(
            'label' => 'Post Grado','required'=>false, 'empty_data'=>'No'
            ));
        $builder->add('areaconocimiento', 'text', array(
     'label' => 'Area Conocimiento del Post-Grado','required'=>false
            ));
        $builder->add('instituto', 'text', array(
    'label' => 'Instituto', 'required'=>false
            ));
        
        $builder->add('cursos', 'textarea', array(
    'label' => 'Cursos Realizados', 'required'=>false
            ));
        
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MisionSucre\RipesBundle\Entity\Academico'
        ));
    }

    public function getName()
    {
        return 'academico';
    }
}
?>