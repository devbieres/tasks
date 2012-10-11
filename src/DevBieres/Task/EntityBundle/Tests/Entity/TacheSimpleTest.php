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


class TacheSimpleTest extends BaseTestCase
{

  protected function getFullName() {  return "DevBieresTaskEntityBundle:TacheSimple"; }

  protected $u1;
  protected $u2;
  protected $p1a;
  protected $p1b;
  protected $p2a;
  protected $p2b;

  protected $pr1;
  protected $pr2;

  /**
   * Set Up
   */
  public function setup() {

    parent::setup();

    $this->tearDown();

    // -1-
    $this->u1 = $this->getUserManager()->getNew();
    $this->u1->setUserName("u1"); $this->u1->setUsernameCanonical("u1"); $this->u1->setEmail("u1@test.com"); $this->u1->setEmailCanonical("u1@test.com"); $this->u1->setPassword("pass");
    $this->getUserManager()->persist($this->u1);

    // -1.1-
    $this->p1a =  $this->getProjetManager()->getNew();
    $this->p1a->setLibelle('p1a'); 
    $this->p1a->setRaccourci('p1a'); 
    $this->p1a->setUser($this->u1); 
    $this->getProjetManager()->persist($this->p1a);

    $this->p1b =  $this->getProjetManager()->getNew();
    $this->p1b->setLibelle('p1b'); 
    $this->p1b->setRaccourci('p1b'); 
    $this->p1b->setUser($this->u1); 
    $this->getProjetManager()->persist($this->p1b);

    // -2-
    $this->u2 = $this->getUserManager()->getNew();
    $this->u2->setUserName("u2"); $this->u2->setUsernameCanonical("u2"); $this->u2->setEmail("u2@test.com"); $this->u2->setEmailCanonical("u2@test.com"); $this->u2->setPassword("pass");
    $this->getUserManager()->persist($this->u2);

    // -2.1-
    $this->p2a =  $this->getProjetManager()->getNew();
    $this->p2a->setRaccourci('p2a'); 
    $this->p2a->setLibelle('p2a'); $this->p2a->setUser($this->u2); $this->getProjetManager()->persist($this->p2a);
    $this->p2b =  $this->getProjetManager()->getNew();
    $this->p2b->setRaccourci('p2b'); 
    $this->p2b->setLibelle('p2b'); $this->p2b->setUser($this->u2); $this->getProjetManager()->persist($this->p2b);


    // -3-
    $this->pr1 = $this->getPrioriteManager()->getNew();
    $this->pr1->setLibelle("Normal"); $this->pr1->setNiveau(1); $this->getPrioriteManager()->persist($this->pr1);
    $this->pr2 = $this->getPrioriteManager()->getNew();
    $this->pr2->setLibelle("Haute"); $this->pr2->setNiveau(2); $this->getPrioriteManager()->persist($this->pr2);

    $this->pr3 = $this->getPrioriteManager()->getNew();
    $this->pr3->setLibelle("Basse"); $this->pr3->setNiveau(0); $this->getPrioriteManager()->persist($this->pr3);

   } // fin de setup

  /**
   * Nettoye ce qui a pu être crée pendant un test
   */
    public function tearDown() {

      // -1-
      $this->getManager()->Purge();
      $this->getProjetManager()->Purge();
      $this->getUserManager()->Purge();
      $this->getPrioriteManager()->Purge();

  } // Fin du tearDown

  /**
   * Retourne le manager
   */
  private $srv;
  protected function getManager() {
    // -0- Chargement du manager
    if($this->srv == null) {
      $this->srv = $this->get('dvb.mng_tachesimple');
    }
    return $this->srv;
  }

  private $srvProjet;
  protected function getProjetManager() {
     if($this->srvProjet == null) {
       $this->srvProjet = $this->get('dvb.mng_projet');
     }
     return $this->srvProjet;
  }

  private $srvPriorite;
  protected function getPrioriteManager() {
     if($this->srvPriorite == null) {
       $this->srvPriorite = $this->get('dvb.mng_priorite');
     }
     return $this->srvPriorite;
  }

  private $srvUser;
  protected function getUserManager() {
     if($this->srvUser == null) {
       $this->srvUser = $this->get('dvb.mng_user');
     }
     return $this->srvUser;
  }

 /**
   * Simple test
  */
  public function testConstruct() {

    // -1-
    $p = $this->getManager()->getNew();

    // -2-
    $this->assertNull($p->getProjet());

    // -3-
    $p->setLibelle("tache simple");
    $this->assertEquals("tache simple", $p->getLibelle());
    $p->setEtat(TacheBase::ETAT_FAIT);
    $this->assertEquals(TacheBase::ETAT_FAIT, $p->getEtat());
    $p->setPriorite($this->pr1);
    $this->assertEquals($this->pr1->getId(), $p->getPriorite()->getId());
    $p->setProjet($this->p1a);
    $this->assertEquals($this->p1a->getId(), $p->getProjet()->getId());

 }

 public function testEtat() {
    // -0-
    $p = $this->getManager()->getNew();

    // -1-
    $p->setEtat(TacheBase::ETAT_FAIT);
    $this->assertEquals('fait', $p->getEtatLibelle());
    $this->assertTrue($p->estFait());
    $this->assertFalse($p->estAFaire());
    $p->setEtat(TacheBase::ETAT_ANNULE);
    $this->assertEquals('annule', $p->getEtatLibelle());
    $this->assertFalse($p->estFait());
    $this->assertFalse($p->estAFaire());
    $p->setEtat(TacheBase::ETAT_AFAIRE);
    $this->assertEquals('afaire', $p->getEtatLibelle());
    $this->assertFalse($p->estFait());
    $this->assertTrue($p->estAFaire());

    // -2-
    $p = $this->getManager()->getNew();
    $this->assertNull($p->getMisALaCorbeille());    
    $dte = new \DateTime();
    sleep(5);
    $p->setEtat(TacheBase::ETAT_FAIT);
    $this->assertNotNull($p->getMisALaCorbeille());    
    $this->assertGreaterThan($dte, $p->getMisALaCorbeille());
    $p->setEtat(TacheBase::ETAT_AFAIRE);
    $this->assertNull($p->getMisALaCorbeille());    
    $dte = new \DateTime();
    sleep(5);
    $p->setEtat(TacheBase::ETAT_ANNULE);
    $this->assertNotNull($p->getMisALaCorbeille());    
    $this->assertGreaterThan($dte, $p->getMisALaCorbeille());
    $p->setEtat(TacheBase::ETAT_AFAIRE);
    $this->assertNull($p->getMisALaCorbeille());    

    
    
  }


  public function testFindActiveByProjet() {

    // -1-
    $this->assertCount(0, $this->getManager()->findActiveByProjet($this->p1a));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p1a, TacheBase::ETAT_AFAIRE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p1a, TacheBase::ETAT_ANNULE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p1a, TacheBase::ETAT_FAIT));
    $this->assertCount(0, $this->getManager()->findActiveByProjet($this->p1b));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p1b, TacheBase::ETAT_AFAIRE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p1b, TacheBase::ETAT_ANNULE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p1b, TacheBase::ETAT_FAIT));
    $this->assertCount(0, $this->getManager()->findActiveByProjet($this->p2a));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2a, TacheBase::ETAT_AFAIRE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2a, TacheBase::ETAT_ANNULE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2a, TacheBase::ETAT_FAIT));
    $this->assertCount(0, $this->getManager()->findActiveByProjet($this->p2b));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_AFAIRE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_ANNULE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_FAIT));

    // -2-
    $t = $this->getManager()->getNew();
    $t->setLibelle("test"); $t->setProjet($this->p1a); $t->setPriorite($this->pr1); $this->getManager()->persist($t);

    // -3-
    $this->assertCount(1, $this->getManager()->findActiveByProjet($this->p1a));
    $this->assertCount(1, $this->getManager()->findByProjet($this->p1a, TacheBase::ETAT_AFAIRE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p1a, TacheBase::ETAT_ANNULE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p1a, TacheBase::ETAT_FAIT));
    $this->assertCount(0, $this->getManager()->findActiveByProjet($this->p1b));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p1b, TacheBase::ETAT_AFAIRE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p1b, TacheBase::ETAT_ANNULE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p1b, TacheBase::ETAT_FAIT));
    $this->assertCount(0, $this->getManager()->findActiveByProjet($this->p2a));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2a, TacheBase::ETAT_AFAIRE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2a, TacheBase::ETAT_ANNULE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2a, TacheBase::ETAT_FAIT));
    $this->assertCount(0, $this->getManager()->findActiveByProjet($this->p2b));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_AFAIRE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_ANNULE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_FAIT));

    // -2-
    $t = $this->getManager()->getNew();
    $t->setLibelle("test"); $t->setProjet($this->p1a); $t->setPriorite($this->pr1); $this->getManager()->persist($t);

    // -3-
    $this->assertCount(2, $this->getManager()->findActiveByProjet($this->p1a));
    $this->assertCount(2, $this->getManager()->findByProjet($this->p1a, TacheBase::ETAT_AFAIRE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p1a, TacheBase::ETAT_ANNULE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p1a, TacheBase::ETAT_FAIT));
    $this->assertCount(0, $this->getManager()->findActiveByProjet($this->p1b));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p1b, TacheBase::ETAT_AFAIRE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p1b, TacheBase::ETAT_ANNULE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p1b, TacheBase::ETAT_FAIT));
    $this->assertCount(0, $this->getManager()->findActiveByProjet($this->p2a));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2a, TacheBase::ETAT_AFAIRE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2a, TacheBase::ETAT_ANNULE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2a, TacheBase::ETAT_FAIT));
    $this->assertCount(0, $this->getManager()->findActiveByProjet($this->p2b));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_AFAIRE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_ANNULE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_FAIT));

    $t->setEtat(TacheBase::ETAT_ANNULE); $this->getManager()->persist($t);
    $this->assertCount(1, $this->getManager()->findActiveByProjet($this->p1a));
    $this->assertCount(1, $this->getManager()->findByProjet($this->p1a, TacheBase::ETAT_AFAIRE));
    $this->assertCount(1, $this->getManager()->findByProjet($this->p1a, TacheBase::ETAT_ANNULE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p1a, TacheBase::ETAT_FAIT));
    $this->assertCount(0, $this->getManager()->findActiveByProjet($this->p1b));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p1b, TacheBase::ETAT_AFAIRE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p1b, TacheBase::ETAT_ANNULE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p1b, TacheBase::ETAT_FAIT));
    $this->assertCount(0, $this->getManager()->findActiveByProjet($this->p2a));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2a, TacheBase::ETAT_AFAIRE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2a, TacheBase::ETAT_ANNULE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2a, TacheBase::ETAT_FAIT));
    $this->assertCount(0, $this->getManager()->findActiveByProjet($this->p2b));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_AFAIRE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_ANNULE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_FAIT));


    $t->setEtat(TacheBase::ETAT_FAIT); $this->getManager()->persist($t);
    $this->assertCount(1, $this->getManager()->findActiveByProjet($this->p1a));
    $this->assertCount(1, $this->getManager()->findByProjet($this->p1a, TacheBase::ETAT_AFAIRE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p1a, TacheBase::ETAT_ANNULE));
    $this->assertCount(1, $this->getManager()->findByProjet($this->p1a, TacheBase::ETAT_FAIT));
    $this->assertCount(0, $this->getManager()->findActiveByProjet($this->p1b));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p1b, TacheBase::ETAT_AFAIRE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p1b, TacheBase::ETAT_ANNULE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p1b, TacheBase::ETAT_FAIT));
    $this->assertCount(0, $this->getManager()->findActiveByProjet($this->p2a));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2a, TacheBase::ETAT_AFAIRE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2a, TacheBase::ETAT_ANNULE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2a, TacheBase::ETAT_FAIT));
    $this->assertCount(0, $this->getManager()->findActiveByProjet($this->p2b));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_AFAIRE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_ANNULE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_FAIT));

    // -2-
    $t = $this->getManager()->getNew();
    $t->setLibelle("test"); $t->setProjet($this->p2a); $t->setPriorite($this->pr1); $this->getManager()->persist($t);
    $this->assertCount(1, $this->getManager()->findActiveByProjet($this->p1a));
    $this->assertCount(1, $this->getManager()->findByProjet($this->p1a, TacheBase::ETAT_AFAIRE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p1a, TacheBase::ETAT_ANNULE));
    $this->assertCount(1, $this->getManager()->findByProjet($this->p1a, TacheBase::ETAT_FAIT));
    $this->assertCount(0, $this->getManager()->findActiveByProjet($this->p1b));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p1b, TacheBase::ETAT_AFAIRE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p1b, TacheBase::ETAT_ANNULE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p1b, TacheBase::ETAT_FAIT));
    $this->assertCount(1, $this->getManager()->findActiveByProjet($this->p2a));
    $this->assertCount(1, $this->getManager()->findByProjet($this->p2a, TacheBase::ETAT_AFAIRE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2a, TacheBase::ETAT_ANNULE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2a, TacheBase::ETAT_FAIT));
    $this->assertCount(0, $this->getManager()->findActiveByProjet($this->p2b));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_AFAIRE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_ANNULE));
    $this->assertCount(0, $this->getManager()->findByProjet($this->p2b, TacheBase::ETAT_FAIT));

  } // Fin de testFindActiveByProjet

  /**
   * Test des requêtes de gestion des tâches par utilisateurs
   */
  public function testFindActiveByUser() {

    // -1-
    $nb1 = count($this->getManager()->findActiveByUser($this->u1));
    $this->assertEquals(0, $nb1);
    $nb2 = count($this->getManager()->findActiveByUser($this->u2));
    $this->assertEquals(0, $nb2);

    // -2-
    $t = $this->getManager()->getNew();
    $t->setLibelle("test"); $t->setProjet($this->p1a); $t->setPriorite($this->pr1); $this->getManager()->persist($t);
    $this->assertCount($nb1 + 1, $this->getManager()->findActiveByUser($this->u1));
    $this->assertCount($nb2, $this->getManager()->findActiveByUser($this->u2));

    // -2-
    $t = $this->getManager()->getNew();
    $t->setLibelle("test"); $t->setProjet($this->p1a); $t->setPriorite($this->pr1); $this->getManager()->persist($t);
    $this->assertCount($nb1 + 2, $this->getManager()->findActiveByUser($this->u1));
    $this->assertCount(2, $this->getManager()->findByUser($this->u1, TacheBase::ETAT_AFAIRE));
    $this->assertCount(0, $this->getManager()->findByUser($this->u1, TacheBase::ETAT_ANNULE));
    $this->assertCount(0, $this->getManager()->findByUser($this->u1, TacheBase::ETAT_FAIT));
    $this->assertCount($nb2, $this->getManager()->findActiveByUser($this->u2));

    $t->setEtat(TacheBase::ETAT_FAIT); $this->getManager()->persist($t);
    $this->assertCount($nb1 + 1, $this->getManager()->findActiveByUser($this->u1));
    $this->assertCount(1, $this->getManager()->findByUser($this->u1, TacheBase::ETAT_AFAIRE));
    $this->assertCount(0, $this->getManager()->findByUser($this->u1, TacheBase::ETAT_ANNULE));
    $this->assertCount(1, $this->getManager()->findByUser($this->u1, TacheBase::ETAT_FAIT));
    $this->assertCount($nb2, $this->getManager()->findActiveByUser($this->u2));

    $t->setEtat(TacheBase::ETAT_ANNULE); $this->getManager()->persist($t);
    $this->assertCount($nb1 + 1, $this->getManager()->findActiveByUser($this->u1));
    $this->assertCount(1, $this->getManager()->findByUser($this->u1, TacheBase::ETAT_AFAIRE));
    $this->assertCount(1, $this->getManager()->findByUser($this->u1, TacheBase::ETAT_ANNULE));
    $this->assertCount(0, $this->getManager()->findByUser($this->u1, TacheBase::ETAT_FAIT));
    $this->assertCount($nb2, $this->getManager()->findActiveByUser($this->u2));

    // -3-
    $t2 = $this->getManager()->getNew();
    $t2->setLibelle("test"); $t2->setProjet($this->p2a); $t2->setPriorite($this->pr2); $this->getManager()->persist($t2);
    $this->assertCount($nb1 + 1, $this->getManager()->findActiveByUser($this->u1));
    $this->assertCount(1, $this->getManager()->findByUser($this->u1, TacheBase::ETAT_AFAIRE));
    $this->assertCount(1, $this->getManager()->findByUser($this->u1, TacheBase::ETAT_ANNULE));
    $this->assertCount(0, $this->getManager()->findByUser($this->u1, TacheBase::ETAT_FAIT));
    $this->assertCount($nb2 + 1, $this->getManager()->findActiveByUser($this->u2));

    // -4-
    $t2 = $this->getManager()->getNew();
    $t2->setLibelle("test"); $t2->setProjet($this->p2b); $t2->setPriorite($this->pr2); $this->getManager()->persist($t2);
    $this->assertCount($nb1 + 1, $this->getManager()->findActiveByUser($this->u1));
    $this->assertCount(1, $this->getManager()->findByUser($this->u1, TacheBase::ETAT_AFAIRE));
    $this->assertCount(1, $this->getManager()->findByUser($this->u1, TacheBase::ETAT_ANNULE));
    $this->assertCount(0, $this->getManager()->findByUser($this->u1, TacheBase::ETAT_FAIT));
    $this->assertCount($nb2 + 2, $this->getManager()->findActiveByUser($this->u2));

  } // testFindByUser

  /**
   * Simple test de validation
   **/
  public function testValidator() {
    // -1-
    $o = $this->getManager()->getNew();
    $this->assertEquals(TacheBase::ETAT_AFAIRE, $o->getEtat());
    $e = $this->getValidator()->validate($o);
    $this->assertCount(3, $e);

    // -2-
    $o->setProjet($this->p1a);
    $e = $this->getValidator()->validate($o);
    $this->assertCount(2, $e);

    // -3-
    $o->setPriorite($this->pr1);
    $e = $this->getValidator()->validate($o);
    $this->assertCount(1, $e);

    // -4-
    $o->setLibelle("Test");
    $e = $this->getValidator()->validate($o);
    $this->assertCount(0, $e);

    // -5-
    $o->setEtat(-1);
    $e = $this->getValidator()->validate($o);
    $this->assertCount(1, $e);
    $o->setEtat(1);
    $e = $this->getValidator()->validate($o);
    $this->assertCount(0, $e);
    $o->setEtat(3);
    $e = $this->getValidator()->validate($o);
    $this->assertCount(1, $e);
    $o->setEtat(2);
    $e = $this->getValidator()->validate($o);
    $this->assertCount(0, $e);
    $o->setEtat(3);
    $e = $this->getValidator()->validate($o);
    $this->assertCount(1, $e);


  } // Fin de testValidator

  /**
   * Test de scenario simple
   */
  public function testScenarioSimple() {
    // -1-
    $nb = count($this->getManager()->findAll());
    $this->assertEquals(0, $nb);

    // -2-
  } // Fin de testScenarioSimple

  /**
   * Test de la requête "find by user"
   */
  public function testFindByUser() {


  } // fin de tesFindByUser

}

