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
use DevBieres\Task\EntityBundle\Entity\Projet;

class ProjetManager extends CodeBaseManager {

  /**
   * Permet de définir un nom complet par defaut pour un repo 
   */
  protected function getFullName() {  return "DevBieresTaskEntityBundle:Projet"; }

  /**
   * Retourne une nouvelle entite
   */
    public function getNew() { return new Projet(); }

    /**
     * Suppression des tâches encore associé au projet
     */
    protected function beforeRemove($projet) {
      // -1-
      $this->getRepo("DevBieresTaskEntityBundle:TacheSimple")->deleteByProjet($projet);
    } // beforeRemove


    /**
     * Liste les projets de l'utilisateur
     * @param $user User l'utilisateur
     */
    public function findByUser($user) {
        return $this->getRepo()->findByUser($user);
    } // Fin de findByUser 

    /**
     * Retourne vrai si l'utilisateur a au moins un projet
     * @param $user User l'utilisateur
     */
    public function hasOneProjet($user) {
        // -1- Récupération des projets
        $col = $this->findByUser($user);
        // -2-
        if(count($col) > 0) { return true; }
        else { return false; }
    } // hasOneProject

} // fin de ProjetManager
