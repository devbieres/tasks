<?php
namespace DevBieres\Task\EntityBundle\Validator;
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

use Symfony\Component\Validator\ExecutionContext;
use Symfony\Component\Validator\Constraint;

/**
 * Une contrainte spéciale permettant de valider l'unicite d'un projet
 * Inspiré de http://michelsalib.com/2011/04/03/create-your-own-constraint-validator-in-symfony2-a-doctrine-unique-validator/
 * @Annotation
 */
class ProjetUnique extends Constraint  {

   public $message = "site.unique";
   public $messageUser = "site.project.userneeded";
   public $entity;
   public $property;

   /**
    * Retourne le service qui gère effectivement la contrainte
    */
   public function validatedBy() { return "validator.projet.unique"; }

   //public function requiredOptions() { return array('entity'); }

   /**
    * Defintion d'une target
    */
   public function getTargets() { return self::CLASS_CONSTRAINT; }


}
