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

use DevBieres\Common\BaseBundle\Manager\BaseManager;
use DevBieres\Task\EntityBundle\Entity\TacheSimple;
use DevBieres\Task\EntityBundle\Entity\Projet;
use DevBieres\Task\EntityBundle\Form\Type\TacheSimpleType;
use DevBieres\Task\EntityBundle\Form\Type\MultiTacheSimpleType;

class TacheSimpleManager extends BaseManager {

  /**
   * Permet de définir un nom complet par defaut pour un repo 
   */
  protected function getFullName() {  return "DevBieresTaskEntityBundle:TacheSimple"; }

  /**
   * Retourne une nouvelle entite
   */
    public function getNew() { return new TacheSimple(); }

    /**
     * Création d'une liste de tâche sur la base d'une chaîne de caractère
     * @param $projet Projet le projet
     * @param $contenu string une chaîne contenant une liste de tache à créer
     */
    public function createMulti($projet, $contenu) {
           // -0-
           $return = 0;
           // -1- Découpage du contenu en lignes
           $text = trim($contenu);
           $text = explode("\n", $text);
           //$text = array_filter($text, 'trim');

           // -2- Pour chaque ligne
           foreach($text as $ligne) {
             // --> Trim de la ligne
             $ligne = trim($ligne);
             // --> Appel du service en mode unitaire
             $return += $this->createFromString($projet, $ligne);
           }

           // -3-
           return $return;
    } // Fin de create multi

    /**
     * Création d'une tâche pour une projet
     * @param $projet Projet le projet
     * @param $contenu string une chaîne contenant une liste de tache à créer
     */
    public function createFromString($projet, $ligne, $mng = NULL) {

        // -0-
        if(is_null($mng)) { 
            $mng = new PrioriteManager($this->getEntityManager());
        }

        // -1- Calcul du niveau
        $p = NULL;
        $pc = substr($ligne,0,1);
        switch($pc) {
            case '+': $p = $mng->getPrioriteHaute(); $ligne = substr($ligne,1); break;
            case '-': $p = $mng->getPrioriteBasse(); $ligne = substr($ligne,1); break;
            default : $p = $mng->getPrioriteNormale(); break;
        }

        // -2-
        $obj = $this->getNew();
        $obj->setPriorite($p);
        $obj->setProjet($projet);
        $obj->setLibelle($ligne);
        $this->persist($obj);        

        // -3-
        return 1;

    } // createFromString

    /**
     * Retourne un tableau dont la clé est un code de priorite
     */
    protected function groupByPriorite($col) {
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
     * @param $filtre String un filtre pour la requete
     */
    public function findActiveByUser($user, $filtre = '') {
        return $this->getRepo()->findByUser($user, $filtre);
    } // Fin de findByUser 

    /**
     * Retourne les tâches de l'utilisateur mais groupée par priorite
     * @param $user User l'utilisateur
     */
    public function findActiveByUserGroupByPriorite($user, $filtre = '') {

      // -1-
      $col = $this->findActiveByUser($user, $filtre);

      // -2-
      return $this->groupByPriorite($col);

    } // Fin de findActiveByUserGroupByPriorite

    /**
     * Liste les tache de l'utilisateur
     * @param $user User l'utilisateur
     * @param $etat interger etat
     */
    public function findByUser($user, $etat) {
        return $this->getRepo()->findByUser($user, '', $etat);
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

 
    /**
     * Valide que l'utilisateur qui met à jour la tâche est bien celui qui possède la tâche
     */
    public function checkUserTache($tache, $user) {
       // -1-
       $code1 = $user->getCode();
       $code2 = $tache->getProjet()->getUser()->getCode();

       // -2-
       return ($code1 == $code2);
    }

   /**
    * Retourne le nombre d'élement dans la corbeille
    */
    public function getNbElementsTrash($user) {
        return count($this->findTrash($user));
    }
    /**
     * Retourne la liste pour la corbeille des utilisateurs
     * @param $user User l'utilisateur
     */
    public function findTrash($user, $filtre = '') {
         return $this->getRepo()->findTrashByUser($user, new  \DateTime(), $filtre);
    }

    /**
     * Supprime la corbeille de l'utilisateur
     * @param $user User l'utilisateur
     */
    public function deleteTrash($user) {
          // -1-
          $col = $this->findTrash($user);

          // -2-
          foreach($col as $t) { $this->remove($t); } 
    }

    /**
     * Supprime les éléments de la corbeille considérer comme trop vieux
     * La différence avec deleteTrash est que deleteTrash supprime tout
     */
    public function clearTrash($user, $nbjours) {
          // -1- Calcul de la date pour la suppression
          $date = new \DateTime();
          $date = $date->sub(new \DateInterval(sprintf("P%sD", $nbjours)));

          // -2-
          $col = $this->getRepo()->findTrashByUser($user, $date);

          // -3- 
          // TODO : centraliser entre delete et clear
          foreach($col as $t) { $this->remove($t); } 


    } // clearTrash

    /**
     * Retourne la liste pour la corbeille des utilisateurs groupées par priorité
     */
    public function findTrashGroupByPriorite($user, $filtre= '') {
        // -1-
        $col = $this->findTrash($user, $filtre);

        // -2-
        return $this->groupByPriorite($col);

    } // Fin de findTrashGroupByPriorite

    /**
     * Retourne le formulaire de création d'une tache simple
     */
    public function getTacheSimpleType() { return new TacheSimpleType(); }
    /**
     * Retourne le formulaire de création d'une tache simple en mode multiple
     */
    public function getMultiTacheSimpleType() { return new MultiTacheSimpleType(); }

}
