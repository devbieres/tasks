<?php
namespace DevBieres\Task\EntityBundle\Manager;
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

use DevBieres\Common\BaseBundle\Manager\CodeBaseManager;
use DevBieres\Task\EntityBundle\Entity\TacheSimple;
use DevBieres\Task\EntityBundle\Entity\Projet;

class TacheSimpleManager extends CodeBaseManager {

  /**
   * Permet de définir un nom complet par defaut pour un repo 
   */
  protected function getFullName() {  return "DevBieresTaskEntityBundle:TacheSimple"; }

  /**
   * Retourne une nouvelle entite
   */
    public function getNew() { return new TacheSimple(); }

    /**
     * Retourne un tableau dont la clé est un code de priorite
     */
    private function groupByPriorite($col) {
      // -1-
      $arrReturn = array();

      // -2- 
      foreach($col as $t) {
        $code = $t->getPriorite()->getCode();
        if(! array_key_exists($code, $arrReturn)) { $arrReturn[$code] = array(); }
        array_push($arrReturn[$code], $t);
      } // Fin de la boucle

      // -3-
      return $arrReturn;
    } // Fin de groupByPriorite

    /**
     * Liste les taches de l'utilisateur
     * @param $user User l'utilisateur
     */
    public function findActiveByUser($user) {
        return $this->getRepo()->findByUser($user);
    } // Fin de findByUser 

    /**
     * Retourne les tâches de l'utilisateur mais groupée par priorite
     * @param $user User l'utilisateur
     */
    public function findActiveByUserGroupByPriorite($user) {

      // -1-
      $col = $this->findActiveByUser($user);

      // -2-
      return $this->groupByPriorite($col);

    } // Fin de findActiveByUserGroupByPriorite

    /**
     * Liste les tache de l'utilisateur
     * @param $user User l'utilisateur
     * @param $etat interger etat
     */
    public function findByUser($user, $etat) {
        return $this->getRepo()->findByUser($user, $etat);
    } // Fin de findByUser 

    /**
     * Liste les taches du projet
     * @param $projet projet le projet 
     */
    public function findActiveByProjet($projet) {
        return $this->getRepo()->findByProjet($projet);
    } // Fin de findByUser 

    /**
     * Liste les taches du projet 
     * @param $projet Projet le projet
     * @param $etat interger etat
     */
    public function findByProjet($projet, $etat) {
        return $this->getRepo()->findByProjet($projet, $etat);
    } // Fin de findByUser 


}
