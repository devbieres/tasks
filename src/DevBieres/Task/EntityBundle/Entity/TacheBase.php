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
use JMS\SerializerBundle\Annotation\Expose;
use JMS\SerializerBundle\Annotation\Exclude;

use DevBieres\Common\BaseBundle\Entity\EntityBase;

/**
 * Entite permettant de stocker des parametres
 * @ORM\MappedSuperClass
 */
abstract class TacheBase extends EntityBase {


    /**
     * Libelle : libelle ou titre ...
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\MinLength(3)
     * @Expose
     */
     protected $libelle;
     public function getLibelle() { return $this->libelle; }
     public function setLibelle($value) { $this->libelle = $value;  }

  // Definition des etats sous forme de constantes
  const ETAT_AFAIRE = 0;
  const ETAT_ANNULE = 1;
  const ETAT_FAIT = 2;

  /**
   * Retourne un tableau contenant la liste des états
   */
  public static function getEtats() {
    return array(TacheBase::ETAT_AFAIRE, TacheBase::ETAT_ANNULE, TacheBase::ETAT_FAIT);
  }

  /**
   * Definit un etat pour la tâche 
   * @ORM\Column(type="integer")
   * @Assert\Choice(callback = "getEtats")
   * @Expose
   */
  protected $etat = TacheBase::ETAT_AFAIRE;
  public function getEtat() { return $this->etat; }
  public function getEtatLibelle() {
     switch($this->getEtat()) {
         case TacheBase::ETAT_ANNULE: return "annule";
         case TacheBase::ETAT_FAIT: return "fait";
     }
     return "afaire";
  }
  public function setEtat($valeur) { 
     $this->etat = $valeur;
     if(($this->etat == TacheBase::ETAT_ANNULE) || ($this->etat == TacheBase::ETAT_FAIT)) {
         $this->misALaCorbeille = new \DateTime();
     } else {
         $this->misALaCorbeille = NULL;
     }
  }

  /**
    * @ORM\Column(type="datetime", nullable=true)
    */
   protected $misALaCorbeille;
   public function getMisALaCorbeille() { return $this->misALaCorbeille; }

  /**
   * Retourne vraie si la tâche est considérée comme à faire
   */
  public function estAFaire() {
     return ($this->getEtat() == TacheBase::ETAT_AFAIRE);
  }
  public function estFait() {
     return ($this->getEtat() == TacheBase::ETAT_FAIT);
  }

 /**
  * La priorité associée à la tâche
  * @ORM\ManyToOne(targetEntity="Priorite")
  * @ORM\JoinColumn(name="priorite_id",   referencedColumnName="id")
  * @Assert\NotNull
  * @Exclude
  */
  protected $priorite;
  public function getPriorite() { return $this->priorite; }
  public function setPriorite($value) { $this->priorite = $value; }

 /**
  * Le projet associée à la tâche
  * @ORM\ManyToOne(targetEntity="Projet")
  * @ORM\JoinColumn(name="projet_id",   referencedColumnName="id")
  * @Assert\NotNull
  * @Exclude
  */
  protected $projet;
  public function getProjet() { return $this->projet; }
  public function setProjet($value) { $this->projet = $value; }

}

