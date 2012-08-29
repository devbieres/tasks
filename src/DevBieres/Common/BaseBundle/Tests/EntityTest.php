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
use DevBieres\Common\BaseBundle\Entity\CodeLibelleBase;

class EntityTest extends TestCase {

   /*
    * Test la méthode de calcul du code
    */
    public function testCalculerCode() {
      //-1-
      $this->assertEquals("lala", CodeLibelleBase::CalculerCode("l A L        a     "));
    } // Fin de testCalculerCode


}
