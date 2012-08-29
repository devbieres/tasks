<?php
namespace DevBieres\Task\UserBundle\Entity;

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
use JMS\SerializerBundle\Annotation\Exclude;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="dvb_user_preference")
 * @ORM\Entity(repositoryClass="DevBieres\Task\UserBundle\Repository\UserPreferenceRepository")
 */
class UserPreference
{

   /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    */
  protected $id;
  public function getId() { return $this->id; }

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

  const FR = "fr";
  const EN = "en";

  /**
   * Retourne un tableau contenant la liste des langue
   */
  public static function getLangue() {
    return array(UserPreference::FR, UserPreference::EN);
  }

  /**
   * Definit la langue sélectionnée par l'utilisateur 
   * @ORM\Column(type="string", length=2)
   * @Assert\Choice(callback = "getLangue")
   */
  protected $locale = UserPreference::FR;
  public function getLocale() { return $this->locale; }
  public function setLocale($valeur) { $this->locale = $valeur; }

  /**
   * Definit le nombre de jours qu'un élement reste dans la corbeille
   * @ORM\Column(type="integer")
   * @Assert\Range(min=1, max=30)
   */
  protected $nbjours = 5;
  public function getNbJours() { return $this->nbjours; }
  public function setNbJours($valeur) { return $this->nbjours = $valeur; }


}
