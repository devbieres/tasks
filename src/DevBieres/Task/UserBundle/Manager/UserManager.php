<?php
namespace DevBieres\Task\UserBundle\Manager;
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

use DevBieres\Common\BaseBundle\Manager\BaseManager;
use DevBieres\Task\UserBundle\Entity\User;
use DevBieres\Task\UserBundle\Entity\UserPreference;
use DevBieres\Task\UserBundle\Form\Type\UserPreferenceType;

class UserManager extends BaseManager {

  /**
   * Permet de définir un nom complet par defaut pour un repo 
   */
  protected function getFullName() {  return "DevBieresTaskUserBundle:User"; }

  public function findOneByUsername($username) { return $this->getRepo()->findOneByUsername($username); }

  protected function getUPRepo() { return $this->getRepo("DevBieresTaskUserBundle:UserPreference"); }

  /**
   * Retourne une nouvelle entite
   */
   public function getNew() { return new User(); }

  /**
   * Retourne une nouvelle entite pour la gestion des préférences
   */
   public function getNewPreference() { return new UserPreference(); }

   /**
    * Recherche les préférences de l'utilisateur
    */
   public function getUserPreference($user) {
      // -1-
      $up = $this->getUPRepo()->findOneByUser($user);

      // -2-
      if(! is_null($up)) { return $up; }

      // -3-
      $up = $this->getNewPreference();
      $up->setUser($user);
      

      // -4-
      $this->persist($up);

      // -5-
      return $up;

   } // Fin de getUserPreference

   /**
    * Retourne le formulaire pour les user préférences
    */
   public function getUserPreferenceType() { return new UserPreferenceType(); }

   /**
    * Gestion d'un beforeRemove
    */
   public function beforeRemove($user) {
       // -1-
       $up = $this->getUserPreference($user, 0);

       // -2-
       if(! is_null($up)) { $this->getEntityManager()->remove($up); $this->flush(); }

   }


}
