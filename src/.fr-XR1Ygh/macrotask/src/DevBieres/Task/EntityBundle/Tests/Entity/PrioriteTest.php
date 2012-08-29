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


class PrioriteTest extends BaseTestCase
{

  protected function getFullName() {  return "DevBieresTaskEntityBundle:Priorite"; }

  /**
   * Set Up
   */
  public function setup() {
    parent::setup();
  }

  /**
   * Nettoye ce qui a pu être crée pendant un test
   */
    public function tearDown() {

      // -1-
      $this->getManager()->Purge();

  } // Fin du tearDown

  /**
   * Retourne le manager
   */
  private $srv;
  protected function getManager() {
    // -0- Chargement du manager
    if($this->srv == null) {
      $this->srv = $this->get('dvb.mng_priorite');
    }
    return $this->srv;
  }

 /**
   * Simple test
  */
  public function testConstruct() {

    // -1-
    $p = $this->getManager()->getNew();

    // -2-
    $p->setNiveau(3);
    $this->assertEquals($p->getNiveau(), 3);
    
  }

  /**
   * Simple creation de scenarion (i / u / d)
   */
  public function testScenarioSimple() {

    // -1-
    $nb = count($this->getManager()->findAll());

    // -2-
    $p = $this->getManager()->getNew();
    $p->setLibelle("Normal");
    $p->setNiveau(1);
    $this->getManager()->persist($p);

    // -3-
    $this->assertCount(($nb + 1), $this->getManager()->findAll());

    // -4-
    $p1 = $this->getManager()->findOneByCode("normal");
    $this->assertNotNull($p1);
    $this->assertEquals($p1->getNiveau(), $p->getNiveau());

     // -5-
     $this->getManager()->remove($p1);
     $this->assertCount($nb, $this->getManager()->findAll());

  } // Fin de testScenarioSimple

  /**
   * Test des validations
   */
  public function testValidation() {
    // -1-
    $p = $this->getManager()->getNew();
    $errors = $this->getValidator()->validate($p);
    $this->assertCount(2, $errors);

    // -2-
    $p->setLibelle("test");
    $p->setNiveau(0);
    $errors = $this->getValidator()->validate($p);
    $this->assertCount(0, $errors);

    // -3-
    $p->setNiveau(-1);
    $errors = $this->getValidator()->validate($p);
    $this->assertCount(1, $errors);

    // -4-
    $p->setNiveau(2);
    $errors = $this->getValidator()->validate($p);
    $this->assertCount(0, $errors);

  }

  /**
   * validation de l'utilisation de la close OrderBy
   */
  public function testOrderBy() {

    // -1- Création de 3 niveaux
    $p2 = $this->getManager()->getNew();
    $p2->setLibelle("Normal");
    $p2->setNiveau(1);

    $this->getManager()->persist($p2);
    $p1 = $this->getManager()->getNew();
    $p1->setLibelle("Basse");
    $p1->setNiveau(0);
    $this->getManager()->persist($p1);

    $p3 = $this->getManager()->getNew();
    $p3->setLibelle("Haute");
    $p3->setNiveau(2);
    $this->getManager()->persist($p3);

    // -2-
    $col = $this->getManager()->findAll();
    $niveau = -1;
    foreach($col as $p) {
      $this->assertGreaterThan($niveau, $p->getNiveau());
      $niveau = $p->getNiveau();
    } // -2-

  } // fin de testOrderBy


}
