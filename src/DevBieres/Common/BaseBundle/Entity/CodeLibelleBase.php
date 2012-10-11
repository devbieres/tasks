<?php
namespace DevBieres\Common\BaseBundle\Entity;
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
 * <nthierry<at>lafamillebn<point>net> wrote this file. As long as you retain this notice you
 * can do whatever you want with this stuff. If we meet some day, and you think
 * this stuff is worth it, you can buy me a beer in return. 
 * ----------------------------------------------------------------------------
 * Plus d'infos : http://fr.wikipedia.org/wiki/Beerware
 * ----------------------------------------------------------------------------
*/

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

use JMS\SerializerBundle\Annotation\Expose;
use JMS\SerializerBundle\Annotation\Exclude;

/**
 * Entité de base contenant un code et un libellé
 * le libelle est calculé sur la base du code
 * @ORM\MappedSuperClass
 * @UniqueEntity("code")
 */
abstract class CodeLibelleBase extends EntityBase 
{

     /**
      * Code : unique pour le type d'entité. Calculé sur la base du libelle
      * @ORM\Column(type="string", length=255, unique=true)
      * @Expose
      */
     protected $code;
     public function getCode() { return $this->code; }

 
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

     /**
      * Description
      * @ORM\Column(type="text")
      */
     protected $description = "";
     public function getDescription() { return $this->description; }
     public function setDescription($value) { $this->description = $value; }

     /**
      * @ORM\PrePersist
      */
     public function prePersist()
     {
         parent::prePersist();
         $this->code = $this->__calculerCode(); //CodeLibelleBase::CalculerCode($this->getLibelle());
         //$this->code =  CodeLibelleBase::CalculerCode($this->getLibelle());  
         // Si ...
         if($this->getDescription() == null) { $this->setDescription(""); } 
     }

     /**
      * @ORM\PreUpdate
      */
     public function preUpdate()
     {
        parent::preUpdate();
        $this->code = $this->__calculerCode(); //CodeLibelleBase::CalculerCode($this->getLibelle());
     }     

     /**
      * Retourne le libelle
      */
     public function __toString() { return sprintf("%s", $this->getLibelle()); }


     /**
      * Calcule le code en interne
      */
     protected function __calculerCode()  {
           return CodeLibelleBase::CalculerCode($this->getLibelle());
     }

     /**
      * Fonction de calcul d'un code
      * @param $libelle string
      */
     public static function CalculerCode($libelle) {
        return str_replace(" ","", strtolower($libelle));
     }
     

} // Fin de CodeLibelleBase
