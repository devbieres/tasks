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
use DevBieres\Task\EntityBundle\Entity\Priorite;

class PrioriteManager extends CodeBaseManager {

  /**
   * Permet de définir un nom complet par defaut pour un repo 
   */
  protected function getFullName() {  return "DevBieresTaskEntityBundle:Priorite"; }

  /**
   * Retourne une nouvelle entite
   */
    public function getNew() { return new Priorite(); }

   /**
    * Spécialisation du service findAll()
    */
   /*
   public function findAll() {
      // -1-
      $col = parent::findAll();

      // -2-
      if(count($col) > 0) { return $col; }

      
      // -3-
      $obj = $this->getNew(); $obj->setLibelle('Basse'); $obj->setNiveau(0); $this->persist($obj);
      $obj = $this->getNew(); $obj->setLibelle('Normal'); $obj->setNiveau(1); $this->persist($obj);
      $obj = $this->getNew(); $obj->setLibelle('Haute'); $obj->setNiveau(2); $this->persist($obj);

      // -4-
      return parent::findAll();


   } */

   /**
    * Retourne la priorite Haute
    * TODO : trouver autre chose que le passage par le code
    */
   public function getPrioriteHaute() {
       return $this->getRepo()->findOneByCode('haute');
   }
   /**
    * Retourne la priorite Basse
    * TODO : trouver autre chose que le passage par le code
    */
   public function getPrioriteBasse() {
       return $this->getRepo()->findOneByCode('basse');
   }
   /**
    * Retourne la priorite Normal 
    * TODO : trouver autre chose que le passage par le code
    */
   public function getPrioriteNormale() {
       return $this->getRepo()->findOneByCode('normal');
   }


}
