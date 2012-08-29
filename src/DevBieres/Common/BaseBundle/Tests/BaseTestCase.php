<?php
namespace DevBieres\Common\BaseBundle\Tests;
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

use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class BaseTestCase extends WebTestCase {

  /**
   * @var Validator
   */
  private $validator;
  public function getValidator() { return $this->validator; }


  /**
   * @var \Doctrine\ORM\EntityManager
   */
  private $em;
  public function getEntityManager() { return $this->em; }

  /**
   * @var Container
   */
  private $container;
  public function getContainer() { return $this->container; }

  public function setUp() {
    // -1- chargement du noyau
    $kernel = static::createKernel();
    $kernel->boot();

    // -2- recupération de l'entity manager
    $this->container = $kernel->getContainer();
    $this->em = $this->get('doctrine.orm.entity_manager');
    $this->validator = $this->get('validator');
    //$this->em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
  }

  /**
   * Permet de définir un namepsace par defaut pour le chargement d'un repo
   */
  protected abstract function getFullName();

  /**
   * Permet de récupérer un repo
   */
  public function getRepo($name = '') {
    //
    if($name == '') { $name = $this->getFullName(); }
    return $this->em->getRepository($name);
  } // Fin de getRepo

  /**
   * Retourne une service en fonction du parametre
   * @param $string param
   */
  public function get($name) {
    return $this->getContainer()->get($name);
  }


} // Fin de BaseTestCase
