<?php
namespace DevBieres\Common\OutilsBundle\Tests\Entity;
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


class ParametreTest extends BaseTestCase
{

  protected function getFullName() { return "DevBieresCommonOutilsBundle:Parametre"; }

  /**
   * Retourne le manager
   */
  private $srv;
  protected function getManager() {
    // -0- Chargement du manager
    if($this->srv == null) {
      $this->srv = $this->get('dvb.mng_parametre');
    }
    return $this->srv;
  }

 /**
   * Simple test
  */
  public function testConstruct() {

    // -1-
    $p = $this->getManager()->getNew();
    $this->assertEquals($p->getDebut(), \DateTime::createFromFormat('Y-m-d H:i:s', '1900-01-01 00:00:00'));  
    $this->assertEquals($p->getFin(), \DateTime::createFromFormat('Y-m-d H:i:s', '2900-12-31 23:59:59'));  
    
  }

  /**
   * Simple creation de scenarion (i / u / d)
   */
  public function testScenarioSimple() {

    $this->getManager()->purge();

    // -1-
    $p = $this->getManager()->getNew();
    $p->setLibelle("param A");
    $p->setValeur("valeur A");
    $this->assertEquals("param A", $p->getLibelle());
    $this->assertEquals($p->getLibelle(), $p->__toString());
    $this->assertEquals("valeur A", $p->getValeur());

    // -2- Validation du nombre
    $nb = count($this->getRepo()->findAll());
    $this->getManager()->persist($p);
    $this->assertCount($nb+1, $this->getRepo()->findAll());
    $this->assertNotNull($p->getCreatedAt());
    $this->assertEquals($p->getCreatedAt(), $p->getUpdatedAt());

    // -3- Chargement par ID
    $p1 = $this->getManager()->findOneById($p->getId());
    $this->assertNotNull($p1);
    $this->assertEquals($p1->getCode(), "parama");

    // -4-
    sleep(10);
    $p1->setValeur('valeur B');
    $this->getManager()->persist($p1);
    $p2 = $this->getManager()->findOneByCode($p1->getCode());
    $this->assertNotNull($p2);
    $this->assertEquals($p1->getValeur(), $p2->getValeur());
    $this->assertGreaterThan($p2->getCreatedAt(), $p2->getUpdatedAt());

    // -Fin-
    $p = $this->getManager()->findOneByCode('parama');
    $this->assertNotNull($p);
    $this->getManager()->remove($p);
    $this->assertCount($nb, $this->getRepo()->findAll());

  } // Fin de testScenarioSimple

  /**
   * Test dans le cas où le code / date n'existe pas
   */
  public function testSansCode() {
    // -1-
    $p = $this->getManager()->findOneByCode("inconnu");
    $this->assertNull($p);

    // -2-
    $debut = new \DateTime();
    $debut->sub(new \DateInterval('P3D'));
    $fin   = new \DateTime();
    $fin->add(new \DateInterval('P5D'));

    // -3-
    $p = $this->getManager()->getNew();
    $p->setLibelle("date");
    $p->setValeur("valeur");
    $p->setDebut($debut);
    $p->setFin($fin);
    $this->getManager()->persist($p);

    // -4-
    $p1 = $this->getManager()->findOneByCode("date");
    $this->assertNotNull($p1);

    // -5-
    $date = new \DateTime();
    $date = $date->sub(new \DateInterval('P10D'));
    $p1 = $this->getManager()->findOneByCodeAndDate('date', $date);
    $this->assertNull($p1);

    // -5-
    $date = new \DateTime();
    $date = $date->sub(new \DateInterval('P4D'));
    $p1 = $this->getManager()->findOneByCodeAndDate('date', $date);
    $this->assertNull($p1);

    // -5-
    $date = new \DateTime();
    $date = $date->sub(new \DateInterval('P3D'));
    $p1 = $this->getManager()->findOneByCodeAndDate('date', $date);
    $this->assertNotNull($p1);

    // -5-
    $date = new \DateTime();
    $p1 = $this->getManager()->findOneByCodeAndDate('date', $date);
    $this->assertNotNull($p1);

    // -5-
    $date = new \DateTime();
    $date = $date->add(new \DateInterval('P4D'));
    $p1 = $this->getManager()->findOneByCodeAndDate('date', $date);
    $this->assertNotNull($p1);


    // -5-
    $date = new \DateTime();
    $date = $date->add(new \DateInterval('P9D'));
    $p1 = $this->getManager()->findOneByCodeAndDate('date', $date);
    $this->assertNull($p1);


    // -6-
    $this->getManager()->remove($p);

  }

  /**
   * Test que la fin doit être supérieur au debut
   */
  public function testFinSupDebut() {
    // -1-
    $p = $this->getManager()->getNew();;
    $errors = $this->getValidator()->validate($p);
    $this->assertCount(2, $errors);
    $p->setLibelle("testFinSupDebut");
    $p->setValeur("testFinSupDebut");
    $errors = $this->getValidator()->validate($p);
    $this->assertCount(0, $errors);

    // -2-
    //$this->assertEquals($p->getDebut(), \DateTime::createFromFormat('Y-m-d H:i:s', '1900-01-01 00:00:00'));  
    $p->setDebut(\DateTime::createFromFormat('Y-m-d H:i:s', '2012-01-01 00:00:00'));
    $p->setFin(\DateTime::createFromFormat('Y-m-d H:i:s', '2011-12-31 00:00:00'));
    $errors = $this->getValidator()->validate($p);
    $this->assertCount(1, $errors);

    // -3-
    $p->setFin(\DateTime::createFromFormat('Y-m-d H:i:s', '2012-01-01 00:00:00'));
    $errors = $this->getValidator()->validate($p);
    $this->assertCount(1, $errors);

    // -4-
    $p->setFin(\DateTime::createFromFormat('Y-m-d H:i:s', '2012-01-02 00:00:00'));
    $errors = $this->getValidator()->validate($p);
    $this->assertCount(0, $errors);

  }



}


?>
