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

class ProjetController extends BaseController 
{

  /**
   * Retourne la liste des projets de l'utilisateur
   */
  public function indexAction() {
    // -1-
    $this->manageUnconnectedUser();

    // -2-
    $col = $this->getProjetManager()->findByUser($this->getUser());

    // -3-
    return $this->render(
      $this->getViewPath('Projet:index'),
      array('col' => $col)
    );

  } // Fin de listAction


  /**
   * Gestion de l'action new et create
   */
  public function newAction(Request $request) {
    // -1-
    $this->manageUnconnectedUser();

    // -2-
    $projet = $this->getProjetManager()->getNew();
    $projet->setUser($this->getUser());

    // -3-
    $form = $this->createFormBuilder($projet)
      ->add('libelle', 'text', array('label' => $this->trans('site.project.label')))
      ->getForm();

    // -4-
    if($request->getMethod() == 'POST') {
      // -4.1-
      $form->bindRequest($request);

      // -4.2-
      if($form->isValid()) {
        // Force l'utilisateur
        $projet->setUser($this->getUser());
        // TODO : Gérer le contrôle d'unicite
        $this->getProjetManager()->persist($projet);
        return $this->redirect( $this->generateUrl('web_projet_index')); 
      } // Fin de -4.2-

    } // Fin de -4-

    // -5-
    return $this->render($this->getViewPath('Projet:new'),
      array('form' => $form->createView())
      );
  } // Fin newAction

  /**
   * Retourne la page de supppression / validation
   * @param int $projet l'identifiant du projet
   */
  public function destroyAction($projet) {

    // -1-
    $projet = $this->getProjetManager()->findOneById($projet);
    if($projet == null) { 
      return $this->redirect( $this->generateUrl('web_projet_index')); 
    }

    // -2-
    $form = $this->createFormBuilder($projet)
      ->add('id', 'hidden')
      ->getForm();

    // -2-
    return $this->render(
                $this->getViewPath('Projet:destroy'),
                array('obj'=> $projet, "form" =>  $form->createView())
              );

  } // Fin de destroy action

  /**
   * Gestion de la validation de la suppression
   */
  public function destroyConfirmedAction(Request $request) {
    // -1-
    $form = $request->get('form');
    $id = $form['id'];

    // -2- Récupération du projet
    $projet = $this->getProjetManager()->findOneById($id);
    if($projet == null) { 
      $this->storeFlash( $this->trans('site.project.unknown')); 
      return $this->redirect( $this->generateUrl('web_projet_index')); 
    }

    // -3- Validation que l'utilisateur est bien celui supprime
    if($projet->getUser()->getCode() != $this->getUser()->getCode()) {
        $this->storeFlash( $this->trans('site.project.wronguser')); 
        return $this->redirect( $this->generateUrl('web_projet_index')); 
    } 

    // -4-
    if($request->getMethod() == 'POST') {
        $this->getProjetManager()->remove($projet);
        $this->storeFlash( $this->trans('site.destroy_confirmed')); 
    } // -4-

    return $this->redirect( $this->generateUrl('web_projet_index')); 
  } // destroyConfirmedAction

}

?>
