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
    $filtre = $this->getFiltre($uri);

    // -3-
    $arr = $this->getTacheManager()->findActiveByUserGroupByPriorite($this->getUser(), $filtre);

    // -4-
    return $this->render(
      $this->getViewPath('Tache:list'),
      array('arr' => $arr)
    );

  } // Fin de listAction

  /**
   * Retourne le formulaire de recherche
   */
  public function filterFormAction($uri, $path) {
    // -1-
    $obj = $this->manageUnconnectedUser(); if($obj != null) { return $obj; }

    // -2- Default Data
    $defaultData = array(
        'filtre'  => $this->getFiltre($uri)
    );
    $form = $this->createFormBuilder($defaultData)
                  ->add('filtre', 'text', array('label' => 'site.filter'))
                  ->getForm();

    // -3-
    return $this->render($this->getViewPath('Tache:filtre'),
                         array('form' => $form->createView(), 'path' => $path)
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
      $this->getViewPath('Tache:list'),
      array('arr' => $arr)
    );

  } // Fin de listAction


  /**
   * Action some new
   * Permet un ajout "multiple" de tâches pour un projet
   */
  public function somenewAction(Request $request) {
 
    // -1-
    $obj = $this->manageUnconnectedUser(); if($obj != null) { return $obj; }

    // -2-
    if(! $this->getProjetManager()->hasOneProjet($this->getUser())) {
      $this->storeFlash($this->trans('site.task.oneproject'));
      return $this->redirect($this->generateUrl('web_home'));
    }

    // -3- Default Data
    $defaultData = array(
        'contenu'  => ''
    );
  
    // -4-
    $form = $this->createForm( 
              $this->getTacheManager()->getMultiTacheSimpleType(),
              $defaultData,
              array('user' => $this->getUser())
            );

    // -5-
    if($request->getMethod() == 'POST') {
       // --> Bind
       $form->bind($request);
       $data = $form->getData();
       // --> Appel du service 
       $count = $this->getTacheManager()->createMulti(
              $data['projet'],
              strip_tags($data['contenu'])
       );
       // Retour
       $this->storeFlash( sprintf('%s:%s', $this->trans('site.task.multinewok'), $count));
       return $this->redirect($this->generateUrl('web_home'));
    }
    

    // -6-
    return $this->render(
                $this->getViewPath('Tache:somenew'),
                array('form' => $form->createView())
               );

  } // somenewAction

  /**
   * Action new
   */
  public function newAction(Request $request) {

    // -1-
    $obj = $this->manageUnconnectedUser(); if($obj != null) { return $obj; }

    // -2-
    if(! $this->getProjetManager()->hasOneProjet($this->getUser())) {
      $this->storeFlash($this->trans('site.task.oneproject'));
      return $this->redirect($this->generateUrl('web_home'));
    } // Fin de -2-

    // -3-
    $tache = $this->getTacheManager()->getNew();

    // -4-
    return $this->__showFormulaire($request, $tache, 'new');

  }  // fin de newAction


  /**
   * Gestion de l'action edit
   */
  public function editAction(Request $request, $tache) {
    // -1-
    $obj = $this->manageUnconnectedUser(); if($obj != null) { return $obj; }
   
    // -2-
    $tache = $this->getTacheManager()->findOneById($tache);
    if($tache == null) {
      $this->storeFlash($this->trans('site.task.unknown'));
      return $this->redirect($this->generateUrl('web_home'));
    }

    // -3-
    $obj = $this->checkUser($tache); if($obj != null) { return $obj; }

    // -4-
    return $this->__showFormulaire($request, $tache, 'edit');

  }

  /**
   * Centralise la validation que la tache est bien celle de l'utilisateur
   */
  protected function  checkUser($tache) {
    // -1-
    if(! $this->getTacheManager()->checkUserTache($tache, $this->getUser())) {
       $this->storeFlash($this->trans('site.forbiden'));
       return $this->redirect($this->generateUrl('web_home'));
    }
  }

  

  /**
   * Affiche le formulaire
   * @param Tache $tache la tâche
   * @param string $path le chemin
   */
  protected function __showFormulaire($request, $tache, $path) {

    // -0-
    $user = $this->getUser();

    // -1-
    /*
    $form = $this->createFormBuilder($tache)
              ->add('libelle', 'text', array('label' => 'site.task.label'))
              ->add('priorite', null,  array('label' => 'site.task.priority'))
              ->add('projet', 'entity', array(
                       'class' => 'DevBieres\Task\EntityBundle\Entity\Projet',
                       'query_builder' => function(EntityRepository $er) use ($user) { return $er->findByUserQuery($user);  },
                       )) 
              ->getForm();
     */
    $form = $this->createForm(
                     $this->getTacheManager()->getTacheSimpleType(),
                     $tache,
                     array('user' => $user)
                  );

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
                    $this->getViewPath('Tache:' . $path),
                    array('form' => $form->createView(), 'obj' => $tache)
                  ); 


  } /* Fin de _showFormulaire */

  public function changeStateAction(Request $request, $tache, $continue, $state) {
    // -1-
    $obj = $this->manageUnconnectedUser(); if($obj != null) { return $obj; }
   
    // -2-
    $tache = $this->getTacheManager()->findOneById($tache);
    if($tache == null) {
      $this->storeFlash($this->trans('site.task.unknown'));
      return $this->redirect($this->generateUrl('web_home'));
    }

    // -3-
    $obj = $this->checkUser($tache); if($obj != null) { return $obj; }

    // -4-
    $tache->setEtat($state);
    $this->getTacheManager()->persist($tache);

    // -5-
    if($continue) { return $this->__showFormulaire($request, $tache, 'new'); }

    // -6-
    return $this->redirect($this->generateUrl('web_home'));
 
  } // Fin de changeStateAction

  /**
   * Suppression d'une tâche ==> en direct car il y  a un passage par la corbeille
   */
  public function destroyAction(Request $request, $tache) {
    // -1-
    $obj = $this->manageUnconnectedUser(); if($obj != null) { return $obj; }
   
    // -2-
    $tache = $this->getTacheManager()->findOneById($tache);
    if($tache == null) {
      $this->storeFlash($this->trans('site.task.unknown'));
      return $this->redirect($this->generateUrl('web_trash'));
    }

    // -3-
    $obj = $this->checkUser($tache); if($obj != null) { return $obj; }

    // -4-
    $this->getTacheManager()->remove($tache);

    // -5-
    return $this->redirect($this->generateUrl('web_trash'));

  } // Fin de destroyAction

}

?>
