<?php
namespace DevBieres\Task\UserBundle\Form\Type;
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

class UserPreferenceType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('locale', 'choice', 
                   array(
                       'label'   => 'site.user.preference.locale',
                       'choices' => array('fr' => 'Français', 'en' => 'English')
                   )
                );
        $builder->add('nbjours', null, array('label' => 'site.user.preference.days'));
        $builder->add('modeecran', 'choice', 
                   array(
                       'label'   => 'site.user.preference.screen',
                       'choices' => array('HTML' => 'site.user.preference.html', 'MOBILE' => 'site.user.preference.mobile' )
                   )
                );
    }

    public function getName()
    {
        return 'UserPreferences';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DevBieres\Task\UserBundle\Entity\UserPreference',
        ));
    }

}
