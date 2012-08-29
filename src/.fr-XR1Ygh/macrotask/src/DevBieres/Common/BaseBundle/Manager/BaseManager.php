<?php
namespace DevBieres\Common\BaseBundle\Manager;
/*
 * ----------------------------------------------------------------------------
 * « LICENCE BEERWARE » (Révision 42):
 * <thierry[at]lafamillebn[point]net> a créé ce fichier. Tant que vous conservez cet avertissement,
 * vous pouvez faire ce que vous voulez de ce truc. Si on se rencontre un jour et
 * que vous pensez que ce truc vaut le coup, vous pouvez me payer une bière en
 * retour. 
 * ----------------------------------------------------------------------------
 * ----------------------------------------------------------------------------
 * "THE BEER-WARE LICENSE" (Revision 42):
 * <thierry[at]lafamillebn[point]net> wrote this file. As long as you retain this notice you
 * can do whatever you want with this stuff. If we meet some day, and you think
 * this stuff is worth it, you can buy me a beer in return. 
 * ----------------------------------------------------------------------------
 * Plus d'infos : http://fr.wikipedia.org/wiki/Beerware
 * ----------------------------------------------------------------------------
*/

/**
 * Classe de base pour les managers d'entité
 */
abstract class BaseManager {

  /**
   * @var EntityManager
   */
  private $em;
  public function getEntityManager() { return $this->em; }

  /**
   * Constructeur
   */
  public function __construct($em) {
       $this->em = $em;
  }


  /**
   * Permet de définir un nom complet par defaut pour un repo 
   */
  protected abstract function getFullName();

  /**
   * Permet de récupérer un repo
   */
  public function getRepo($name = '') {
    //
    if($name == '') { $name = $this->getFullName(); }
    return $this->getEntityManager()->getRepository($name);
  } // Fin de getRepo


  /**
   * Persist une entite au près du manager et (par defaut) effectue le flush associe
   * @param $entity l'entité à persister
   * @param $flush (par defaut = 1)
   */
  public function persist($entity, $flush = 1) {
    $this->getEntityManager()->persist($entity);
    if($flush == 1) { $this->flush(); }
  } // Fin de persist


  /**
   * Supprimer une entite au près du manager et (par defaut) effectue le flush associe
   * @param $entity l'entité à persister
   * @param $flush (par defaut = 1)
   */
  public function remove($entity, $flush = 1) {
    // -1- Appel de la méthode spécialisable beforeRemove()
    $this->beforeRemove($entity);

    // -2-
    $this->getEntityManager()->remove($entity);

    // -3-
    if($flush == 1) { $this->flush(); }
  } // Fin de persist

  /**
   * Si il y a des choses à faire avant une suppression
   */
  protected function beforeRemove($entity) {

  } // Fin de beforeRemove


  /**
   * raccourci vers le méthode de l'entity manager
   */
  public function flush() {
      $this->getEntityManager()->flush();
  }

   /**
    * Redirection vers l'appel du repo
    */
   public function findOneById($id) {
         return $this->getRepo()->findOneById($id);
   } // Fin de findOneById

  /**
   * Redirection vers l'appel du repo
   */
  public function findAll() {
        return $this->getRepo()->findAll();
  }

  /**
   * Boucle sur chaque entite et supprime l'élément
   * NE PAS UTILISER EN PROD
   */
  public function purge() {
       // -1-
       $col = $this->findAll();
       // -2-
       foreach($col as $obj) { $this->remove($obj); }
  } // Fin de purge

} 

