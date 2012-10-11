<?php
namespace DevBieres\Task\EntityBundle\Form\Type;
/*
 * ----------------------------------------------------------------------------
 * « LICENCE BEERWARE » (Révision 42):
 * <thierry<at>lafamillebn<point>net> a créé ce fichier. Tant que vous conservez cet avertissement,
 * vous pouvez faire ce que vous voulez de ce truc. Si on se rencontre un jour et
 * que vous pensez que ce truc vaut le coup, vous pouvez me payer une bière en
 * retour. 
 * ----------------------------------------------------------------------------
 * ----------------------------------------------------------------------------
 * "THE BEER-WARE LICENSE" (Revision 42):
 * <thierry<at>lafamillebn<point>net> wrote this file. As long as you retain this notice you
 * can do whatever you want with this stuff. If we meet some day, and you think
 * this stuff is worth it, you can buy me a beer in return. 
 * ----------------------------------------------------------------------------
 * Plus d'infos : http://fr.wikipedia.org/wiki/Beerware
 * ----------------------------------------------------------------------------
*/

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class MultiTacheSimpleType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // -1- Récupération du type de rendu pour le champs date
        $widget = $options['date_widget'];
        if($widget == NULL) { $widget = 'choice'; }

        // -2-
        $builder
           //->add('projet', 'entity', array(
           //         'class' => 'DevBieres\Task\EntityBundle\Entity\Projet',
           //         'query_builder' => function(EntityRepository $er) use ($options) { return $er->findByUserQuery($options['user']);  },
           //     ))
           ->add('planif', 'date', array(
                    'label' => 'site.task.planif',
                    'input' => 'datetime',
                    'widget' => $widget,
                    'required' => false,
                    'empty_value' => '',
                    'format' => 'yyyy-MM-dd',
                    'attr'   => array('class' => 'formulaire_date' )
                 ))
           ->add('contenu', 'textarea', array('label' => 'site.task.multi'));

    }

    public function getName()
    {
        return 'MultiTacheSimple';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        // deux options
        $resolver->setDefaults(array(
          'user' => null,
          'date_widget' => null
        ));
    }
}
