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
use DevBieres\Task\UserBundle\Entity\UserPreference;


class UserPreferenceTest extends BaseTestCase
{

  protected function getFullName() {  return "DevBieresTaskUserBundle:UserPreference"; }
  protected $u1;

  /**
   * Set Up
   */
  public function setup() {
    parent::setup();
    // -1-
    $this->u1 = $this->getManager()->getNew();
    $this->u1->setUserName("u1"); $this->u1->setUsernameCanonical("u1"); $this->u1->setEmail("u1@test.com"); $this->u1->setEmailCanonical("u1@test.com"); $this->u1->setPassword("pass");
    $this->getManager()->persist($this->u1);

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
      $this->srv = $this->get('dvb.mng_user');
    }
    return $this->srv;
  }

 /**
   * Simple test
  */
  public function testConstruct() {

    // -1-
    $p = $this->getManager()->getNewPreference();

    // -2-
    $this->assertEquals($p->getLocale(), UserPreference::FR);
    $this->assertEquals($p->getNbJours(), 5);

    // -3-
    $p->setUser($this->u1);
    $this->assertEquals($this->u1->getCode(), $p->getUser()->getCode());
    $p->setLocale(UserPreference::EN);
    $this->assertEquals($p->getLocale(), UserPreference::EN);
    $p->setNbJours(30);
    $this->assertEquals($p->getNbJours(), 30);
  }

  public function testGetLangue() {

    $arr = UserPreference:: getLangue();
    $this->assertCount(2, $arr);
    $this->assertEquals(UserPreference::FR, $arr[0]);
    $this->assertEquals(UserPreference::EN, $arr[1]);

  }

  public function testGetUserPreference() {
     // -1-
     $p = $this->getManager()->getUserPreference($this->u1);
     $this->assertEquals($p->getLocale(), UserPreference::FR);
     $this->assertEquals($p->getNbJours(), 5);

      // -2-
     $u = $this->getManager()->getUserPreference($this->u1);
     $this->assertEquals($p->getId(), $u->getId());

     // -3-
     $this->getManager()->remove($this->u1);

     // -4-
     $this->assertNull($this->getManager()->findOneById($u->getId()));

  }

  public function testValidType() {
    // L'idée c'est juste de valider que le service ne retourne pas null ...
    $this->assertNotNull($this->getManager()->getUserPreferenceType());
 

  }

}
