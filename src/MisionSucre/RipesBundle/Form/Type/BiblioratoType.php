<?php
namespace MisionSucre\RipesBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AmbienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $proms=array();
        
                foreach(range(1,50) as $n)
                    $proms[$n]=$n;
        
        $builder->                 
             add('vagon', 'text', array('label' => 'vagon','required' =>true));
            
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MisionSucre\RipesBundle\Entity\Ambiente'
        ));
    }

    public function getName()
    {
        return 'form';
    }
}
?>