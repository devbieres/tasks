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
use Symfony\Component\Validator\ConstraintValidator;

class ProjetUniqueValidator extends ConstraintValidator {

   /**
    * Passage du manager projet
    */
  private $mng;

  public function __construct($mng) { $this->mng = $mng; }

  /**
   * Spécialisation du service de validation
   */
    public function validate($entity, Constraint $constraint) {

      // -1- Récupération du user
      $user = $entity->getUser();
      if(is_null($user)) {  $this->context->addViolationAtSubPath('user', $constraint->messageUser); return; }

      // -2-
      $obj = $this->mng->findOneByUserAndRaccourci($user, $entity->getRaccourci());
      if(! is_null($obj)) {  $this->context->addViolationAtSubPath('raccourci', $constraint->message); }

       // -3-
       $code = $this->mng->CalculerCode($entity);
      $obj = $this->mng->findOneByCode($code);
      if(! is_null($obj)) {  $this->context->addViolationAtSubPath('code', $constraint->message); }

  } // fin isValid

}
