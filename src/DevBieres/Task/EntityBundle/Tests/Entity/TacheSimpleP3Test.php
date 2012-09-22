<?php
namespace DevBieres\Task\EntityBundle\Tests\Entity;
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

use DevBieres\Common\BaseBundle\Tests\BaseTestCase;
//use DevBieres\Common\OutilsBundle\Entity\Parametre;
use DevBieres\Task\EntityBundle\Entity\Projet;
use DevBieres\Task\EntityBundle\Entity\TacheBase;
use DevBieres\Task\EntityBundle\Manager\TacheSimpleManager;


class TacheSimpleP3Test extends TacheSimpleTest
{

   /**
    * Test
    */
   public function testPlanif() {
     // -1-
     $t = $this->getManager()->getNew();
     $t->setLibelle("test"); $t->setProjet($this->p1a); $t->setPriorite($this->pr1); $this->getManager()->persist($t);

     // -2-
     $arr = $this->getManager()->findActiveByUserGroupByDate($this->u1);
     $this->assertCount(1, $arr[TacheSimpleManager::DATE_UNKNOWN]); 
     $this->assertCount(0, $arr[TacheSimpleManager::DATE_LATE]); 
     $this->assertCount(0, $arr[TacheSimpleManager::DATE_TODAY]); 
     $this->assertCount(0, $arr[TacheSimpleManager::DATE_TOMORROW]); 
     $this->assertCount(0, $arr[TacheSimpleManager::DATE_LATER]); 

     // -3- Date du jour
     $date = new \DateTime();
     $dateJ = \DateTime::createFromFormat('Y-m-d H:i:s', $date->format('Y-m-d') . ' 00:00:00');

     // -4- 
     $add = new \DateInterval("P1D");
     $dateJ->sub($add);
     $t->setPlanif($dateJ);
     $this->getManager()->persist($t);

     // -5-
     $arr = $this->getManager()->findActiveByUserGroupByDate($this->u1);
     $this->assertCount(0, $arr[TacheSimpleManager::DATE_UNKNOWN]); 
     $this->assertCount(1, $arr[TacheSimpleManager::DATE_LATE]); 
     $this->assertCount(0, $arr[TacheSimpleManager::DATE_TODAY]); 
     $this->assertCount(0, $arr[TacheSimpleManager::DATE_TOMORROW]); 
     $this->assertCount(0, $arr[TacheSimpleManager::DATE_LATER]); 

     // -4- 
     $add = new \DateInterval("P1D");
     $dateJ->add($add);
     $t->setPlanif($dateJ);
     $this->getManager()->persist($t);

     // -5-
     $arr = $this->getManager()->findActiveByUserGroupByDate($this->u1);
     $this->assertCount(0, $arr[TacheSimpleManager::DATE_UNKNOWN]); 
     $this->assertCount(0, $arr[TacheSimpleManager::DATE_LATE]); 
     $this->assertCount(1, $arr[TacheSimpleManager::DATE_TODAY]); 
     $this->assertCount(0, $arr[TacheSimpleManager::DATE_TOMORROW]); 
     $this->assertCount(0, $arr[TacheSimpleManager::DATE_LATER]); 

     // -4- 
     $add = new \DateInterval("P1D");
     $dateJ->add($add);
     $t->setPlanif($dateJ);
     $this->getManager()->persist($t);

     // -5-
     $arr = $this->getManager()->findActiveByUserGroupByDate($this->u1);
     $this->assertCount(0, $arr[TacheSimpleManager::DATE_UNKNOWN]); 
     $this->assertCount(0, $arr[TacheSimpleManager::DATE_LATE]); 
     $this->assertCount(0, $arr[TacheSimpleManager::DATE_TODAY]); 
     $this->assertCount(1, $arr[TacheSimpleManager::DATE_TOMORROW]); 
     $this->assertCount(0, $arr[TacheSimpleManager::DATE_LATER]); 

     // -4- 
     $add = new \DateInterval("P1D");
     $dateJ->add($add);
     $t->setPlanif($dateJ);
     $this->getManager()->persist($t);

     // -5-
     $arr = $this->getManager()->findActiveByUserGroupByDate($this->u1);
     $this->assertCount(0, $arr[TacheSimpleManager::DATE_UNKNOWN]); 
     $this->assertCount(0, $arr[TacheSimpleManager::DATE_LATE]); 
     $this->assertCount(0, $arr[TacheSimpleManager::DATE_TODAY]); 
     $this->assertCount(0, $arr[TacheSimpleManager::DATE_TOMORROW]); 
     $this->assertCount(1, $arr[TacheSimpleManager::DATE_LATER]); 
      
   }

}
