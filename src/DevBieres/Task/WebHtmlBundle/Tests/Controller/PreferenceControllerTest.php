<?php
namespace DevBieres\Task\WebHtmlBundle\Tests\Controller;

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

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use DevBieres\Task\UserBundle\Entity\UserPreference;

class PreferenceControllerTest extends ConnecteControllerTest {

       /**
        * Test de la page A propos
        */
       public function testSimple() {

            // -1-
            $crawler = $this->client->request('GET','/html/');
            $this->assertTrue($crawler->filter('#yourtasks')->count() > 0);
            $this->assertTrue($crawler->filter('li.item')->count() == 0);
            $this->assertTrue($crawler->filter('li.item_titre')->count() == 0);
            $this->assertTrue($crawler->filter('.flash ul li')->count() == 0);


            // -2-
            $link = $crawler->filter("#nav_preferences")->eq(0)->link();
            $crawler = $this->client->click($link);
            $this->assertTrue($crawler->filter('#preferences')->count() > 0);
            $obj = $crawler->filter("input#UserPreferences_nbjours")->eq(0)->extract(array('value'));
            $this->assertEquals("5", $obj[0]);

            // -3-
            $form = $crawler->selectButton('submit')->form(array(
                   'UserPreferences[locale]' => UserPreference::EN,
                   'UserPreferences[nbjours]' => 10,));
            $crawler = $this->client->submit($form);
            //$crawler = $this->client->followRedirect();  
            $this->assertTrue($crawler->filter('.flash ul li')->count() == 1);
            
            // -4-
            $obj = $crawler->filter("input#UserPreferences_nbjours")->eq(0)->extract(array('value'));
            $this->assertEquals("10", $obj[0]);


       } 

}

