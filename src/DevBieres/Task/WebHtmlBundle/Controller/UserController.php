<?php
namespace DevBieres\Task\WebHtmlBundle\Controller;

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
use Symfony\Component\HttpFoundation\Request;

class UserController extends BaseController
{

   /**
    * Action qui demander la confirmation de la suppression
    */
    public function destroyAction()
    {
       // -1-
       $obj = $this->manageUnconnectedUser(); if($obj != null) { return $obj; }

       // -2-
       return $this->render('DevBieresTaskWebHtmlBundle:User:destroy.html.twig');
    }


    /**
     * Action effectue la suppression du compte
     */
    public function destroyConfirmedAction(Request $request) {
  
       // -1-
       $obj = $this->manageUnconnectedUser(); if($obj != null) { return $obj; }

       // -2-
       if($request->getMethod() == 'POST') {
          // TODO : mettre un contrôle plus fort comme la validation du mot de passe à nouveau
          $this->getProjetManager()->deleteUserProject($this->getUser());
          $this->getUserManager()->remove($this->getUser());

          // ==> redirection vers la page de logout
          return $this->redirect($this->generateUrl('fos_user_security_logout'));

       } // -2-

       // -3-
       return $this->render('DevBieresTaskWebHtmlBundle:User:destroy.html.twig');
    }
}
