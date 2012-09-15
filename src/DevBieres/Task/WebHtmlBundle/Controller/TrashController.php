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
use DevBieres\Common\BaseBundle\Controller\SecuredController;

use Symfony\Component\HttpFoundation\Request;

class TrashController extends BaseController 
{

    /**
     * Gestion d'une action gérant le rendu du menu corbeille
     */
    public function navMenuAction($route = 'web_trash', $att = '') {

       // -1- Récupération du nombre d'éléments dans la corbeille
       $nb = $this->getTacheManager()->getNbElementsTrash($this->getUser());

       // -2-
       return $this->render($this->getViewPath('Trash:menu'),
                            array('nombre' => $nb, 'route' => $route, 'att' => $att)
                           );

    }

    /**
     * Retourne une liste de tâches corbeille pour l'utilusateur
     */
    public function listTrashAction($uri) {
       // -1-
       $obj = $this->manageUnconnectedUser(); if($obj != null) { return $obj; }

       // -2- Gestion d'un eventuel filtre
       $filtre = $this->getFiltre($uri);

       // -3-
       $arr = $this->getTacheManager()->findTrashGroupByPriorite($this->getUser(), $filtre);

       // -4-
       return $this->render(
          $this->getViewPath('Trash:list'),
          array('arr' => $arr)
       );

    } // Fin de listAction

    /**
     * Action index ==> liste les tâches ANNULE ou FAIT
     */
    public function indexAction() {

       // -1-
       $obj = $this->manageUnconnectedUser(); if($obj != null) { return $obj; }

       // -2-
       return $this->render($this->getViewPath('Trash:index'));
    } // Fin de l'action index

    /**
     * Retourne un formulaire permettant de supprimer toutes les tâches "corbeille"
     */
    public function emptyAllAction() {

       // -1-
       return $this->render($this->getViewPath('Trash:emptyAll'));

    }

    /**
     * Effectue le "vidage" de la corbeille
     */
    public function emptyAllConfirmedAction(Request $request) {

       // -1-
       $obj = $this->manageUnconnectedUser(); if($obj != null) { return $obj; }

       // -2-
       if($request->getMethod() == 'POST') {
             $this->getTacheManager()->deleteTrash($this->getUser());
       } else {
              $this->storeFlash($this->trans('site.task.canemptyall'));
       }

       // -4-
       return $this->redirectToHome();

    } // Fin d'emptyAllConfirmedAction
}
