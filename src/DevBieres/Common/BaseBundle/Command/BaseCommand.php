<?php
namespace DevBieres\Common\BaseBundle\Command;
/*
 * ----------------------------------------------------------------------------
 * « LICENCE BEERWARE » (Révision 42):
 * <nantesparcours@lafamillebn.net> a créé ce fichier. Tant que vous conservez cet avertissement,
 * vous pouvez faire ce que vous voulez de ce truc. Si on se rencontre un jour et
 * que vous pensez que ce truc vaut le coup, vous pouvez me payer une bière en
 * retour. 
 * ----------------------------------------------------------------------------
 * ----------------------------------------------------------------------------
 * "THE BEER-WARE LICENSE" (Revision 42):
 * <nantesparcours@lafamillebn.net> wrote this file. As long as you retain this notice you
 * can do whatever you want with this stuff. If we meet some day, and you think
 * this stuff is worth it, you can buy me a beer in return. 
 * ----------------------------------------------------------------------------
 * Plus d'infos : http://fr.wikipedia.org/wiki/Beerware
 * ----------------------------------------------------------------------------
*/

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Classe de base pour les commandes
 * Le code est inspiré de la page http://symfony.com/doc/2.0/cookbook/console.html
 */
abstract class BaseCommand extends ContainerAwareCommand
{

    /**
     * Spécialisation
     */
    protected function configure()
    {
        // Une option gérable autrement mais c'est pour faire une option :o)
        $this->addOption('debug', null, InputOption::VALUE_NONE, 'Pour avoir plus d infos');

        
    } // Fin de configure

    /**
     * Retourne l'entity manager
     */
    protected function getEntityManager() {
         return $this->getContainer()->get('doctrine')->getEntityManager();
    }


    /**
     * Namespace utilisé pour le chargement des repos
     */
    protected abstract function getNameSpace();

    /**
     * Retourne le repository
     * @param string $nom nom de la classe pour lequel on cherche le repository
     */    
    protected function getRepo($nom) {
          return $this->getEntityManager()->getRepository(sprintf('%s:%s', $this->getNameSpace(),$nom));
    } // Fin de getRepo

     /**
      * retourne la fonction de serialization
      */
    protected function getSerializer() { return $this->getContainer()->get("serializer"); }

      /**
       * Retourne un service par son nom
       */
      protected function get($nom) {
           return $this->getContainer()->get($nom);
      }
}

?>
