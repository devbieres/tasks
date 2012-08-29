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


class TacheSimpleP2Test extends TacheSimpleTest
{

   /**
    * Test
    */
   public function testCreateFromString() {
       // -1-
       $this->assertCount(0, $this->getManager()->findActiveByProjet($this->p2b));
       $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_AFAIRE));
       $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_ANNULE));
       $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_FAIT));

       // -2-
       $this->getManager()->createFromString(
                 $this->p2b,
                 'Première tache');

       // -3-
       $this->assertCount(1, $this->getManager()->findActiveByProjet($this->p2b));
       $this->assertCount(1, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_AFAIRE));
       $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_ANNULE));
       $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_FAIT));
       $col = $this->getManager()->findActiveByProjet($this->p2b);
       $this->assertEquals($col[0]->getPriorite()->getNiveau(), 1);
       
       // -4-
       $this->getManager()->createFromString(
                 $this->p2b,
                 '+Première tache');

       // -5-
       $this->assertCount(2, $this->getManager()->findActiveByProjet($this->p2b));
       $this->assertCount(2, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_AFAIRE));
       $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_ANNULE));
       $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_FAIT));
       $col = $this->getManager()->findActiveByProjet($this->p2b);
       $this->assertEquals($col[0]->getPriorite()->getNiveau(), 2);
       $this->assertEquals($col[1]->getPriorite()->getNiveau(), 1);
       
       // -6-
       $this->getManager()->createFromString(
                 $this->p2b,
                 '-Deuxième tache');

       // -5-
       $this->assertCount(3, $this->getManager()->findActiveByProjet($this->p2b));
       $this->assertCount(3, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_AFAIRE));
       $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_ANNULE));
       $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_FAIT));
       $col = $this->getManager()->findActiveByProjet($this->p2b);
       $this->assertEquals($col[0]->getPriorite()->getNiveau(), 2);
       $this->assertEquals($col[1]->getPriorite()->getNiveau(), 1);
       $this->assertEquals($col[2]->getPriorite()->getNiveau(), 0);
       
   }

   public function testCreateMulti() {
       // -1-
       $this->assertCount(0, $this->getManager()->findActiveByProjet($this->p2b));
       $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_AFAIRE));
       $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_ANNULE));
       $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_FAIT));

       // -2-
       $this->getManager()->createMulti(
                 $this->p2b,
                 "-Deuxième tache\r\nTache Normale   \r\n+TacheHaute\r\n-Tache Basse\r\n  Tache Normae2");

       // -3-
       $this->assertCount(5, $this->getManager()->findActiveByProjet($this->p2b));
       $this->assertCount(5, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_AFAIRE));
       $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_ANNULE));
       $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_FAIT));
       $col = $this->getManager()->findActiveByProjet($this->p2b);
       $this->assertEquals($col[0]->getPriorite()->getNiveau(), 2);
       $this->assertEquals($col[1]->getPriorite()->getNiveau(), 1);
       $this->assertEquals($col[2]->getPriorite()->getNiveau(), 1);
       $this->assertEquals($col[3]->getPriorite()->getNiveau(), 0);
       $this->assertEquals($col[4]->getPriorite()->getNiveau(), 0);
       
      
   }

   /**
    * Test de findActiveByUserGroupByPriorite
    */
   public function testFindActiveByUserGroupByPriorite() {
       // -1- Creation des tâches
       $this->getManager()->createMulti(
                 $this->p2b,
                 "-Deuxième tache\r\nTache Normale   \r\n+TacheHaute\r\n-Tache Basse\r\n  Tache Normae2");
         
       $this->getManager()->createMulti(
                 $this->p2a,
                 "-Deuxième tache\r\nTache Normale   \r\n+TacheHaute\r\n-Tache Basse\r\n  Tache Normae2");

       $this->getManager()->createMulti(
                 $this->p1a,
                 "-Deuxième tache\r\nTache Normale   \r\n+TacheHaute\r\n-Tache Basse\r\n  Tache Normae2");


       // -2- u2 sans filtre
       $arr = $this->getManager()->findActiveByUserGroupByPriorite($this->u2);
       $this->assertCount(3, $arr);
       $this->assertCount(2, $arr["haute"]);
       $this->assertCount(4, $arr["normal"]);
       $this->assertCount(4, $arr["basse"]);
       // -3- u2 avec filtre
       $arr = $this->getManager()->findActiveByUserGroupByPriorite($this->u2, "Norm");
       $this->assertCount(1, $arr);
       $this->assertCount(4, $arr["normal"]);
       // -4- u2 avec filtre
       $arr = $this->getManager()->findActiveByUserGroupByPriorite($this->u2, "Haute");
       $this->assertCount(1, $arr);
       $this->assertCount(2, $arr["haute"]);
       
   } /* Fin de testfindActiveByUserGroupByPriorite */

  /**
   * Test checkUserTache($projet, $user)
   */
  public function testCheckUserTache() {
     
    $t = $this->getManager()->getNew();
    $t->setLibelle("test"); $t->setProjet($this->p1a); $t->setPriorite($this->pr1); $this->getManager()->persist($t);

    $this->assertTrue($this->getManager()->checkUserTache($t, $this->u1));
    $this->assertFalse($this->getManager()->checkUserTache($t, $this->u2));

  } /* Fin de testCheckUserTache */


  /**
   * Test de la partie trash :)
   */
  public function testTrash() {
       // -1- Creation des tâches
       $this->getManager()->createMulti(
                 $this->p2b,
                 "-Deuxième tache\r\nTache Normale   \r\n+TacheHaute\r\n-Tache Basse\r\n  Tache Normae2");
       $this->getManager()->createMulti(
                 $this->p1a,
                 "-Deuxième tache\r\nTache Normale   \r\n+TacheHaute\r\n-Tache Basse\r\n  Tache Normae2");

       $col = $this->getManager()->findActiveByUser($this->u2);
       foreach($col as $t) { $t->setEtat(TacheBase::ETAT_FAIT); $this->getManager()->persist($t); }

       // --> creation de nouvelle tâche active
       $this->getManager()->createMulti(
                 $this->p2b,
                 "-Deuxième tache\r\nTache Normale   \r\n+TacheHaute\r\n-Tache Basse\r\n  Tache Normae2");

       // -2-
       $this->assertEquals(5, $this->getManager()->getNbElementsTrash($this->u2));
       $this->assertCount(5, $this->getManager()->findTrash($this->u2));
       $this->assertCount(2, $this->getManager()->findTrash($this->u2, "Norm"));
       $this->assertCount(1, $this->getManager()->findTrash($this->u2, "Deux"));

       // -3-
       $arr = $this->getManager()->findTrashGroupByPriorite($this->u2);
       $this->assertCount(3, $arr);
       $this->assertCount(1, $arr["haute"]);
       $this->assertCount(2, $arr["normal"]);
       $this->assertCount(2, $arr["basse"]);
       // -3- u2 avec filtre
       $arr = $this->getManager()->findTrashGroupByPriorite($this->u2, "Norm");
       $this->assertCount(1, $arr);
       $this->assertCount(2, $arr["normal"]);
       // -4- u2 avec filtre
       $arr = $this->getManager()->findTrashGroupByPriorite($this->u2, "Haute");
       $this->assertCount(1, $arr);
       $this->assertCount(1, $arr["haute"]);
   
  } /***/

  public function testClearTrash() {

       // -1- Creation des tâches
       $this->getManager()->createMulti(
                 $this->p2b,
                 "-Deuxième tache\r\nTache Normale   \r\n+TacheHaute\r\n-Tache Basse\r\n  Tache Normae2");
       $this->getManager()->createMulti(
                 $this->p1a,
                 "-Deuxième tache\r\nTache Normale   \r\n+TacheHaute\r\n-Tache Basse\r\n  Tache Normae2");

       $col = $this->getManager()->findActiveByUser($this->u2);
       foreach($col as $t) { $t->setEtat(TacheBase::ETAT_FAIT); $this->getManager()->persist($t); }

       // --> creation de nouvelle tâche active
       $this->getManager()->createMulti(
                 $this->p2b,
                 "-Deuxième tache\r\nTache Normale   \r\n+TacheHaute\r\n-Tache Basse\r\n  Tache Normae2");
       $col = $this->getManager()->findActiveByUser($this->u1);
       foreach($col as $t) { $t->setEtat(TacheBase::ETAT_FAIT); $this->getManager()->persist($t); }

       // -2-
       $this->assertEquals(5, $this->getManager()->getNbElementsTrash($this->u2));
       $this->assertEquals(5, $this->getManager()->getNbElementsTrash($this->u1));
       
       // -3-
       $this->getManager()->clearTrash($this->u2, 1); // ne fait rien
       $this->assertEquals(5, $this->getManager()->getNbElementsTrash($this->u2));
       $this->assertEquals(5, $this->getManager()->getNbElementsTrash($this->u1));

       // -4-
       $this->getManager()->clearTrash($this->u2, 0); // supprime tout
       $this->assertEquals(0, $this->getManager()->getNbElementsTrash($this->u2));
       $this->assertEquals(5, $this->getManager()->getNbElementsTrash($this->u1));
  }

  public function testDeleteTrash() {

       // -1- Creation des tâches
       $this->getManager()->createMulti(
                 $this->p2b,
                 "-Deuxième tache\r\nTache Normale   \r\n+TacheHaute\r\n-Tache Basse\r\n  Tache Normae2");
       $this->getManager()->createMulti(
                 $this->p1a,
                 "-Deuxième tache\r\nTache Normale   \r\n+TacheHaute\r\n-Tache Basse\r\n  Tache Normae2");

       $col = $this->getManager()->findActiveByUser($this->u2);
       foreach($col as $t) { $t->setEtat(TacheBase::ETAT_FAIT); $this->getManager()->persist($t); }

       // --> creation de nouvelle tâche active
       $this->getManager()->createMulti(
                 $this->p2b,
                 "-Deuxième tache\r\nTache Normale   \r\n+TacheHaute\r\n-Tache Basse\r\n  Tache Normae2");
       $col = $this->getManager()->findActiveByUser($this->u1);
       foreach($col as $t) { $t->setEtat(TacheBase::ETAT_FAIT); $this->getManager()->persist($t); }

       // -2-
       $this->assertEquals(5, $this->getManager()->getNbElementsTrash($this->u2));
       $this->assertEquals(5, $this->getManager()->getNbElementsTrash($this->u1));

       // -3-
       $this->getManager()->deleteTrash($this->u2);
       $this->assertEquals(0, $this->getManager()->getNbElementsTrash($this->u2));
       $this->assertEquals(5, $this->getManager()->getNbElementsTrash($this->u1));

  }

  public function testType() {
     // C'est pour valider les noms et le fait que cela le retourne pas null
     $obj = $this->getManager()->getTacheSimpleType();
     $this->assertNotNull($obj);
     $this->assertEquals($obj->getName(),'TacheSimple');
     // C'est pour valider les noms et le fait que cela le retourne pas null
     $obj = $this->getManager()->getMultiTacheSimpleType();
     $this->assertNotNull($obj);
     $this->assertEquals($obj->getName(),'MultiTacheSimple');

  }
}
