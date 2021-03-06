<?php
namespace DevBieres\Common\OutilsBundle\Manager;

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
use DevBieres\Common\OutilsBundle\Entity\Parametre;

class ParametreManager extends CodeBaseManager {

  /**
   * Permet de définir un nom complet par defaut pour un repo 
   */
  protected function getFullName() {  return "DevBieresCommonOutilsBundle:Parametre"; }

  /**
   * Retourne une nouvelle entite
   */
    public function getNew() { return new Parametre(); }

    /**
     * Redirection vers l'appel du repo
     */
    public function findOneByCodeAndDate($code, $date) {
          return $this->getRepo()->findOneByCodeAndDate($code, $date);
    } // Fin de findOneById


}
