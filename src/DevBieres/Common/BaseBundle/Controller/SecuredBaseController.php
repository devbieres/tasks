<?php
namespace DevBieres\Common\BaseBundle\Controller;
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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecuredBaseController extends BaseController 
{

      /**
       * Retourne le context de securite
       */
      protected function getSecurityContext() { return $this->get('security.context'); }

      /**
       * Retourne vrai si un utilisateur est connecte
       */
      protected function isUserConnected() { return $this->getSecurityContext()->isGranted('ROLE_USER'); }

      /**
       * Retourne l'utilisateur 
       */
      public function getUser() { return $this->getSecurityContext()->getToken()->getUser(); }

      /**
       * Retourne vers la page de login si l'utilisateur n'est pas connecte
       **/
       protected function manageUnconnectedUser() {
           // Retour à la page login au besoin:
           if(! $this->isUserConnected()) { return $this->redirect('fos_user_security_login'); }
       }

}

?>
