<?php
namespace DevBieres\Task\CommandBundle\Command;
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

use DevBieres\Common\BaseBundle\Command\BaseCommand;

/**
 * Commande qui "nettoie" ce qui aurait put être crée sur les comptes demo
 */
class DemoCommand extends BaseCommand
{

    protected function getNameSpace() { 'DevBieresTaskEntityBundle'; } 


    /**
     * Spécialisation
     */
    protected function configure()
    {
        $this
           ->setName('devbieres:task:demo')
           ->setDescription('Gestion des comptes de demo');

        // Une option gérable autrement mais c'est pour faire une option :o)
        $this->addOption('debug', null, InputOption::VALUE_NONE, 'Pour avoir plus d infos');

        
    } // Fin de configure

    /**
     * Le nom est assez  explicite : ca fait le cafe
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
       // -0- Option
       $debug = $input->getOption('debug');
       if($debug) { $output->writeln('Gestion des comptes de demo'); }

       // -1- Gestion des managers
       $mngUser = $this->get('dvb.mng_user');
       $mngProjet = $this->get('dvb.mng_projet');

       // --> Compte de demo web
       $user = $mngUser->findOneByUserName('demoweb');
       if($user == null) {
         // Création
         $user = $mngUser->getNew();
         $user->setUserName("demoweb"); 
         $user->setUsernameCanonical("demoweb"); 
         $user->setEmail("demoweb@test.com"); 
         $user->setEmailCanonical("demoweb@test.com"); 
         $user->setPlainPassword("demoweb");
         $user->setEnabled(true);
         $mngUser->persist($user);
       } else {
          // Suppression des projets du compte
         $mngProjet->deleteUserProject($user);
       } // Fin du compte demo web
       // Mise à jour des préférences
       $up = $mngUser->getUserPreference($user);
       $up->setModeEcran("HTML");
       $mngUser->persist($up);

       // --> Compte de demo web
       $user = $mngUser->findOneByUserName('demomobile');
       if($user == null) {
         // Création
         $user = $mngUser->getNew();
         $user->setUserName("demomobile"); 
         $user->setUsernameCanonical("demomobile"); 
         $user->setEmail("demomobile@test.com"); 
         $user->setEmailCanonical("demomobile@test.com"); 
         $user->setPlainPassword("demomobile");
         $user->setEnabled(true);
         $mngUser->persist($user);
       } else {
          // Suppression des projets du compte
         $mngProjet->deleteUserProject($user);
       } // Fin du compte demo web
       // Mise à jour des préférences
       $up = $mngUser->getUserPreference($user);
       $up->setModeEcran("MOBILE");
       $mngUser->persist($up);

       // -Fin-
       if($debug) { $output->writeln('Fin de la gestion des comptes'); }

    } // Fin de l'execute

}

?>
