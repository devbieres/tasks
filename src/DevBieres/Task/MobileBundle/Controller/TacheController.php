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

class TacheController extends BaseController 
{

  /**
   * Retourne une liste de tâches pour l'utilusateur
   */
  public function listAction($uri) {
    // -1-
    $obj = $this->manageUnconnectedUser(); if($obj != null) { return $obj; }
    $request = $this->getRequest();

    // -2- Gestion d'un eventuel filtre
    //$filtre = $this->getFiltre($uri);

    // -3-
    $arr = $this->getTacheManager()->findActiveByUserGroupByPriorite($this->getUser());

    // -4-
    return $this->render(
      $this->getViewPath('Tache:list'),
      array('arr' => $arr)
    );

  } // Fin de listAction


  /**
   * Definit l'action show pour une tache
   */
  public function showAction($id) {
    // -1-
    $obj = $this->manageUnconnectedUser(); if($obj != null) { return $obj; }

    // -2-
    $tache = $this->getTacheManager()->findOneById($id);

    // -3-
    return $this->render(
        $this->getViewPath('Tache:show'),
        array('obj' => $tache)
    );

  } // Fin de l'action show
}

?>
