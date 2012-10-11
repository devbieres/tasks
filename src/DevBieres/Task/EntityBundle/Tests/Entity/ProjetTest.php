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


class ProjetTest extends BaseTestCase
{

  protected function getFullName() {  return "DevBieresTaskEntityBundle:Projet"; }

  protected $u1;
  protected $u2;

  /**
   * Set Up
   */
  public function setup() {
    parent::setup();
    // -1-
    $this->u1 = $this->getUserManager()->getNew();
    $this->u1->setUserName("u1"); $this->u1->setUsernameCanonical("u1"); $this->u1->setEmail("u1@test.com"); $this->u1->setEmailCanonical("u1@test.com"); $this->u1->setPassword("pass");
    $this->getUserManager()->persist($this->u1);

    // -2-
    $this->u2 = $this->getUserManager()->getNew();
    $this->u2->setUserName("u2"); $this->u2->setUsernameCanonical("u2"); $this->u2->setEmail("u2@test.com"); $this->u2->setEmailCanonical("u2@test.com"); $this->u2->setPassword("pass");
    $this->getUserManager()->persist($this->u2);


   } // fin de setup

  /**
   * Nettoye ce qui a pu être crée pendant un test
   */
    public function tearDown() {

      // -1-
      $this->getManager()->Purge();
      $this->getUserManager()->Purge();

  } // Fin du tearDown

  /**
   * Retourne le manager
   */
  private $srv;
  protected function getManager() {
    // -0- Chargement du manager
    if($this->srv == null) {
      $this->srv = $this->get('dvb.mng_projet');
    }
    return $this->srv;
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
    $this->assertNull($p->getUser());
    
  }

  /**
   * Simple test de validation
   **/
  public function testValidator() {
    // -1-
    $o = $this->getManager()->getNew();
    $e = $this->getValidator()->validate($o);
    $this->assertCount(4, $e);

    // -2-
    $o->setLibelle("test");
    $e = $this->getValidator()->validate($o);
    $this->assertCount(3, $e);

    // -3-
    $o->setUser($this->u1);
    $e = $this->getValidator()->validate($o);
    $this->assertCount(1, $e);

    // -4-
    $o->setRaccourci("testrac");
    $e = $this->getValidator()->validate($o);
    $this->assertCount(0, $e);

    // -5-
    $o->setUser(NULL);
    $e = $this->getValidator()->validate($o);
    // Un utilisateur vide entraîne deux erreurs :)
    $this->assertCount(2, $e);

  } // Fin de testValidator

  /**
   * Test de scenario simple
   */
  public function testScenarioSimple() {
    // -1-
    $nb = count($this->getManager()->findAll());
    $this->assertEquals(0, $nb);
    $this->assertFalse($this->getManager()->hasOneProjet($this->u1)); 
    $this->assertFalse($this->getManager()->hasOneProjet($this->u2)); 

    // -2-
    $o = $this->getManager()->getNew();
    $o->setLibelle("Projet de TEst");
    $o->setUser($this->u1);
    $this->getManager()->persist($o);
    $this->assertCount($nb+1, $this->getManager()->findAll());
    $this->assertTrue($this->getManager()->hasOneProjet($this->u1)); 
    $this->assertFalse($this->getManager()->hasOneProjet($this->u2)); 

    // -3-
    $o1 = $this->getManager()->findOneByCode("u1_projetdetest");
    $this->assertNotNull($o1);

    // -4-
    $this->getManager()->remove($o1);
    $this->assertCount($nb, $this->getManager()->findAll());
    $this->assertFalse($this->getManager()->hasOneProjet($this->u1)); 
    $this->assertFalse($this->getManager()->hasOneProjet($this->u2)); 

  } // Fin de testScenarioSimple

  /**
   * Test de la requête "find by user"
   */
  public function testFindByUser() {

    // -1-
    $nb1 = count($this->getManager()->findByUser($this->u1));
    $nb2 = count($this->getManager()->findByUser($this->u2));

    // -2-
    $p = $this->getManager()->getNew();
    $p->setLibelle("PU1 A"); $p->setUser($this->u1); $this->getManager()->persist($p);
    $this->assertCount($nb1 + 1, $this->getManager()->findByUser($this->u1));
    $this->assertCount($nb2, $this->getManager()->findByUser($this->u2));

    // -3-
    $p = $this->getManager()->getNew();
    $p->setLibelle("PU1 B"); $p->setUser($this->u1); $this->getManager()->persist($p);
    $this->assertCount($nb1 + 2, $this->getManager()->findByUser($this->u1));
    $this->assertCount($nb2, $this->getManager()->findByUser($this->u2));

    // -4-
    $p = $this->getManager()->getNew();
    $p->setLibelle("PU2 A"); $p->setUser($this->u2); $this->getManager()->persist($p);
    $this->assertCount($nb1 + 2, $this->getManager()->findByUser($this->u1));
    $this->assertCount($nb2 + 1, $this->getManager()->findByUser($this->u2));

    // -5-
    $p = $this->getManager()->getNew();
    $p->setLibelle("PU2 B"); $p->setUser($this->u2); $this->getManager()->persist($p);
    $p = $this->getManager()->getNew();
    $p->setLibelle("PU2 C"); $p->setUser($this->u2); $this->getManager()->persist($p);
    $this->assertCount($nb1 + 2, $this->getManager()->findByUser($this->u1));
    $this->assertCount($nb2 + 3, $this->getManager()->findByUser($this->u2));


  } // fin de tesFindByUser

  /**
   *
   */
  public function testDeleteUserProject() {

    $this->assertCount(0, $this->getManager()->findByUser($this->u1));
    $this->assertCount(0, $this->getManager()->findByUser($this->u2));

    $p = $this->getManager()->getNew();
    $p->setLibelle("PU1 A"); $p->setUser($this->u1); $this->getManager()->persist($p);
    $p = $this->getManager()->getNew();
    $p->setLibelle("PU1 B"); $p->setUser($this->u1); $this->getManager()->persist($p);
    $p = $this->getManager()->getNew();
    $p->setLibelle("PU1 C"); $p->setUser($this->u1); $this->getManager()->persist($p);

    $p = $this->getManager()->getNew();
    $p->setLibelle("PU2 A"); $p->setUser($this->u2); $this->getManager()->persist($p);
    $p = $this->getManager()->getNew();
    $p->setLibelle("PU2 B"); $p->setUser($this->u2); $this->getManager()->persist($p);
    $p = $this->getManager()->getNew();
    $p->setLibelle("PU2 C"); $p->setUser($this->u2); $this->getManager()->persist($p);


    $this->assertCount(3, $this->getManager()->findByUser($this->u1));
    $this->assertCount(3, $this->getManager()->findByUser($this->u2));

   $this->getManager()->deleteUserProject($this->u1);
   $this->assertCount(0, $this->getManager()->findByUser($this->u1));
   $this->assertCount(3, $this->getManager()->findByUser($this->u2));

   $this->getManager()->deleteUserProject($this->u2);
   $this->assertCount(0, $this->getManager()->findByUser($this->u1));
   $this->assertCount(0, $this->getManager()->findByUser($this->u2));

  } // Fin du test DeleteUserProject

}

