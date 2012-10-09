<?php
namespace DevBieres\Task\EntityBundle\Entity;
/*
 * ----------------------------------------------------------------------------
 * « LICENCE BEERWARE » (Révision 42):
 * <thierry<at>lafamillebn<point>net> a créé ce fichier. Tant que vous conservez cet avertissement,
 * vous pouvez faire ce que vous voulez de ce truc. Si on se rencontre un jour et
 * que vous pensez que ce truc vaut le coup, vous pouvez me payer une bière en
 * retour. 
 * ----------------------------------------------------------------------------
 * ----------------------------------------------------------------------------
 * "THE BEER-WARE LICENSE" (Revision 42):
 * <thierry<at>lafamillebn<point>net> wrote this file. As long as you retain this notice you
 * can do whatever you want with this stuff. If we meet some day, and you think
 * this stuff is worth it, you can buy me a beer in return. 
 * ----------------------------------------------------------------------------
 * Plus d'infos : http://fr.wikipedia.org/wiki/Beerware
 * ----------------------------------------------------------------------------
*/

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
//use Symfony\Component\Validator\ExecutionContext;
use JMS\SerializerBundle\Annotation\Exclude;

use DevBieres\Common\BaseBundle\Entity\CodeLibelleBase;

/**
 * Entite permettant de stocker des parametres
 * @ORM\Entity(repositoryClass="DevBieres\Task\EntityBundle\Repository\ProjetRepository")
 * @ORM\Table(name="dvb_task_projet")
 */
class Projet extends CodeLibelleBase {

  /**
   * Les projets sont attachés à des utilisateurs
   * c'est au travers des projets que l'on va chercher les projets d'un utilisateur
   * @ORM\ManyToOne(targetEntity="DevBieres\Task\UserBundle\Entity\User")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   * @Assert\NotNull
   * @Exclude
   */
  protected $user;
  public function getUser() { return $this->user; }
  public function setUser($valeur) { $this->user = $valeur; }

  /**
   * Spécialisation du calcul de code
   */
  protected function __calculerCode() { 
    return Projet::CalculerProjetCode( $this->getUser(), parent::__calculerCode()); 
  }

  /**
   * @param $user l'utilisateur
   * @param $code le code
   */
  public static function CalculerProjetCode($user, $code) {
    return sprintf('%s_%s', $user->getCode(), $code);

  } // Fin de CalculerCode

}

