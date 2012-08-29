<?php
namespace DevBieres\Common\OutilsBundle\Entity;
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
use Symfony\Component\Validator\ExecutionContext;

use DevBieres\Common\BaseBundle\Entity\CodeLibelleBase;

/**
 * Entite permettant de stocker des parametres
 * @ORM\Entity(repositoryClass="DevBieres\Common\OutilsBundle\Repository\ParametreRepository")
 * @ORM\Table(name="dvb_parametre")
 * @Assert\Callback(methods={"isFinValid"})
 */
class Parametre extends CodeLibelleBase {

   /**
    * Constructeur par defaut
    */
   public function __construct() {
      // Defintion des valeurs par defaut
     $this->setDebut(\DateTime::createFromFormat('Y-m-d H:i:s', '1900-01-01 00:00:00'));
     $this->setFin(\DateTime::createFromFormat('Y-m-d H:i:s', '2900-12-31 23:59:59'));
   }

   /**
    * La valeur du parametre
    * @ORM\Column(type="string", length=255)
    * @Assert\NotBlank
    */
   protected $valeur;
   public function getValeur() { return $this->valeur; }
   public function setValeur($value) { $this->valeur = $value; }

   /**
    * @ORM\Column(type="datetime")
    */
   protected $debut;
   public function getDebut() { return $this->debut; }
   public function setDebut($value) { $this->debut = $value; }


   /**
    * @ORM\Column(type="datetime")
    */
   protected $fin;
   public function getFin() { return $this->fin; }
   public function setFin($value) { $this->fin = $value; }

   /**
    * Validation que la fin est supérieur au debut
    */ 
   public function isFinValid(ExecutionContext $context) {
     if($this->getFin() <= $this->getDebut() ){ 
        $context->addViolationAtSubPath('fin','End must be superior to Begin');
       }
   } // Fin de isFinValid



} // Fin de Parametre
