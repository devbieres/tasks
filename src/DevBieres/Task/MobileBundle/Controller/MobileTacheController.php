<?php
namespace DevBieres\Task\MobileBundle\Controller;
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
use Doctrine\ORM\EntityRepository;
use DevBieres\Common\BaseBundle\Controller\SecuredController;

use Symfony\Component\HttpFoundation\Request;
use DevBieres\Task\WebHtmlBundle\Controller\TacheController;

class MobileTacheController extends TacheController 
{

  protected function getViewPath($name) { return sprintf("DevBieresTaskMobileBundle:%s.html.twig", $name); }

  /**
   * Spécialisation de redirectToHome
   */
  protected function redirectToHome() { return $this->redirect($this->generateUrl('mobile_home')); }
  /**
   * Spécialisation de redirectToTrash
   */
  protected function redirectToTrash() { return $this->redirect($this->generateUrl('mobile_trash')); }

  /**
   * Retourne le widget pour les dates
   */
  protected function getDateWidget() { return "single_text"; } 

  /**
   * Action pour passer à la tache suivante
   */
  public function nextAction($id) {
       // -1-
       $obj = $this->manageUnconnectedUser(); if($obj != null) { return $obj; }
       // -2-
       $origine = $this->getTacheManager()->findOneById($id);
       // -3-
       $tache = $this->getTacheManager()->findOneNext($this->getUser(), $origine);
       if($tache==null) { $tache = $origine; }

       // -3- // Todo : centraliser
       return $this->render(
          $this->getViewPath("Tache:show"),
          array('obj' => $tache)
       );

  } // Fin de nextAction

  /**
   * Action pour passer à la tache suivante
   */
  public function previousAction($id) {
      return $this->showAction($id);
  } // Fin de nextAction


  /**
   * Definit l'action show pour une tache
   */
  public function showAction($id, $view = "Tache:show") {
    // -1-
    $obj = $this->manageUnconnectedUser(); if($obj != null) { return $obj; }

    // -2-
    $tache = $this->getTacheManager()->findOneById($id);

    // -3-
    return $this->render(
        $this->getViewPath($view),
        array('obj' => $tache)
    );

  } // Fin de l'action show

}

?>
