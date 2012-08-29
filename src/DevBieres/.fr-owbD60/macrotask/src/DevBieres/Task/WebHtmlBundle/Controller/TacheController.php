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

class TacheController extends BaseController 
{

  /**
   * Retourne une liste de tâches pour l'utilusateur
   */
  public function listAction() {
    // -1-
    $this->manageUnconnectedUser();

    // -2-
    $arr = $this->getTacheManager()->findActiveByUserGroupByPriorite($this->getUser());

    // -3-
    return $this->render(
      $this->getViewPath('Tache:list'),
      array('arr' => $arr)
    );

  } // Fin de listAction

  /**
   * Action new
   */
  public function newAction(Request $request) {

    // -1-
    $this->manageUnconnectedUser();

    // -2-
    if(! $this->getProjetManager()->hasOneProjet($this->getUser())) {
      $this->storeFlash($this->trans('site.task.oneproject'));
      return $this->redirect($this->generateUrl('web_home'));
    } // Fin de -2-

    // -3-
    $tache = $this->getTacheManager()->getNew();

    // -4-
    $form = $this->createFormBuilder($tache)
              ->add('libelle', 'text', array('label' => $this->trans('site.task.label')))
              ->add('priorite', null,  array('label' => $this->trans('site.task.priority')))
              ->add('projet',   null,  array('label' => $this->trans('site.task.project')))
              ->getForm();

    // -5-
    if($request->getMethod() == 'POST') {
      // -5.1-
      $form->bindRequest($request);
      // -5.2-
      if($form->isValid()) {
        // --> Sauvegarde
        $this->getTacheManager()->persist($tache);
        // --> redirection
        return $this->redirect($this->generateUrl('web_home'));
      } // Fin de -5.2-

    } // Fin de -5-

    // -6-
    return $this->render(
                    $this->getViewPath('Tache:new'),
                    array('form' => $form->createView())
                  ); 

  }  // fin de newAction


}

?>
