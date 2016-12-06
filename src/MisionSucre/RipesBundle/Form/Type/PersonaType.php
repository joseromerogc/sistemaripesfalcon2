<?php
namespace MisionSucre\RipesBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $builder->add('priNom', 'text',array('label' => 'Primer Nombre*'));
        $builder->add('segNom', 'text',array('label' => 'Segundo Nombre','required'=>false));
        $builder->add('priApe', 'text',array('label' => 'Primer Apellido*'));
        $builder->add('segApe', 'text',array('label' => 'Segundo Apellido','required'=>false));
       
        $builder->add('celPer', 'text',array('label' => 'Celular','required'=>false));
        $builder->add('telPer', 'text',array('label' => 'Teléfono Fijo','required'=>false));
        $builder->add('codCed', 'choice', array(
        'choices' => array(0=>'V',1=>'E'),'label' => 'Cédula*','empty_data'=>0
            ));
        $builder->add('sexPer', 'choice', array(
        'choices' => array('f'=>'Femenino','m'=>'Masculino'),'label' => 'Sexo*','placeholder'=>'Seleccione'
            ));
        
        $builder->add('edoCiv', 'choice', array(
        'choices' => array('solter@'=>'Solter@','casad@'=>'Casad@','viud@'=>'Viud@','concubinato'=>'Concubinato',
            'Divorciad@'=>'Divorciad@'
            ),'label' => 'Estado Civil'
            ));
        $builder->add('sexPer', 'choice', array(
        'choices' => array('f'=>'Femenino','m'=>'Masculino'),'label' => 'Sexo*','placeholder'=>'Seleccione'
            ));
        $builder->add('cedPer', 'number',array('max_length'=>'9'));
        $builder->add('edadPer', 'number',array('max_length'=>'2','label' => 'Edad','required'=>'false'));
        $builder->add('fechPer','date', array('label' => 'Fecha de Nacimiento','years'=>range(1920,1998)));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MisionSucre\RipesBundle\Entity\Persona'
        ));
    }

    public function getName()
    {
        return 'persona';
    }
}
?>