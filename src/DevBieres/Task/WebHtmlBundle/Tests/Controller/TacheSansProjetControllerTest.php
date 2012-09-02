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

class TacheSansProjetControllerTest extends ConnecteControllerTest {

       /**
        * Test de la page A propos
        */
       public function testDoitAvoirUnProjet() {

            // -1-
            $crawler = $this->client->request('GET','/html/');
            $this->assertTrue($crawler->filter('#yourtasks')->count() > 0);
            $this->assertTrue($crawler->filter('li.item')->count() == 0);
            $this->assertTrue($crawler->filter('.flash ul li')->count() == 0);

            // -2-
            $crawler = $this->client->request('GET','/html/tache/new');
            // Lutilisateur n'a pas de projet, il est donc redirigé vers la page d'accueil
            $crawler = $this->client->followRedirect();  
            $this->assertTrue($crawler->filter('#yourtasks')->count() > 0);
            $this->assertTrue($crawler->filter('li.item')->count() == 0);
            $this->assertTrue($crawler->filter('.flash ul li')->count() == 1);


            // -3-
            $crawler = $this->client->request('GET','/html/tache/somenew');
            // Lutilisateur n'a pas de projet, il est donc redirigé vers la page d'accueil
            $crawler = $this->client->followRedirect();  
            $this->assertTrue($crawler->filter('#yourtasks')->count() > 0);
            $this->assertTrue($crawler->filter('li.item')->count() == 0);
            $this->assertTrue($crawler->filter('.flash ul li')->count() == 1);

       } /* Fin du test simple */


       public function testEditTacheInconnue() {

            // -1-
            $crawler = $this->client->request('GET','/html/');
            $this->assertTrue($crawler->filter('#yourtasks')->count() > 0);
            $this->assertTrue($crawler->filter('li.item')->count() == 0);
            $this->assertTrue($crawler->filter('.flash ul li')->count() == 0);

            // -2-
            $crawler = $this->client->request('GET','/html/tache/edit/10');
            // Lutilisateur n'a pas de projet, il est donc redirigé vers la page d'accueil
            $crawler = $this->client->followRedirect();  
            $this->assertTrue($crawler->filter('#yourtasks')->count() > 0);
            $this->assertTrue($crawler->filter('li.item')->count() == 0);
            $this->assertTrue($crawler->filter('.flash ul li')->count() == 1);

            // -3-
            $crawler = $this->client->request('GET','/html/tache/done/10');
            // Lutilisateur n'a pas de projet, il est donc redirigé vers la page d'accueil
            $crawler = $this->client->followRedirect();  
            $this->assertTrue($crawler->filter('#yourtasks')->count() > 0);
            $this->assertTrue($crawler->filter('li.item')->count() == 0);
            $this->assertTrue($crawler->filter('.flash ul li')->count() == 1);

            // -4-
            $crawler = $this->client->request('GET','/html/tache/destroy/10');
            // Lutilisateur n'a pas de projet, il est donc redirigé vers la page d'accueil
            $crawler = $this->client->followRedirect();  
            $this->assertTrue($crawler->filter('#yourtrash')->count() > 0);
            $this->assertTrue($crawler->filter('li.item')->count() == 0);
            $this->assertTrue($crawler->filter('.flash ul li')->count() == 1);


            

       }


} // Fin de TacheControllerTest

