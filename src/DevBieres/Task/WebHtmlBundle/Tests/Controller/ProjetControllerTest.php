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

class ProjetControllerTest extends ConnecteControllerTest {

       /**
        * Test de la page A propos
        */
       public function testCreationSimple() {

            // -1-
            $crawler = $this->client->request('GET','/');
            $link = $crawler->filter("#nav_projet")->eq(0)->link();
            $crawler = $this->client->click($link);
            $this->assertTrue($crawler->filter('#yourproject')->count() > 0);
            $this->assertTrue($crawler->filter('li.item')->count() == 0);

            // -2- Click sur le lien ajouter d'un projet
            $link = $crawler->filter("#new_project")->eq(0)->link();
            $crawler = $this->client->click($link);
            $this->assertTrue($crawler->filter('#newproject')->count() > 0);

            // -3- recherche du formulaire
            $form = $crawler->selectButton('submit')->form(array(
               'form[libelle]'                 => 'premier projet',
               ));
            $this->client->submit($form);
            $crawler = $this->client->followRedirect();  
            $this->assertTrue($crawler->filter('#yourproject')->count() > 0);
            $this->assertTrue($crawler->filter('li.item')->count() == 1);
            $this->assertTrue($crawler->filter('html:contains("premier projet")')->count() > 0);

            // -4- Suppression du projet
            $link = $crawler->filter("a[title='Supprimer']")->eq(0)->link();
            $crawler = $this->client->click($link);
            $this->assertTrue($crawler->filter('html:contains("[premier projet]")')->count() > 0);
            $this->assertTrue($crawler->filter('#destroyprojet')->count() > 0);

            // -5- Gestion du form
            $form = $crawler->selectButton('submit')->form(array(
               ));
            $this->client->submit($form);
            $crawler = $this->client->followRedirect();  

            // -6-
            $this->assertTrue($crawler->filter('#yourproject')->count() > 0);
            $this->assertTrue($crawler->filter('li.item')->count() == 0);

       }  // testCreationSimple

       /**
        * Deux méthodes du controller font un contrôle particulier en cas d'id inconnu
        */
       public function testIdInconnu() {

            // -1-
            $crawler = $this->client->request('GET','/projet/destroy/8');
            $crawler = $this->client->followRedirect();   // redirection vers /html/projet
            $this->assertTrue($crawler->filter('#yourproject')->count() > 0);

            // -2-
            $crawler = $this->client->request('POST','/projet/destroy_confirmed', array('form[id]' => 8));
            $crawler = $this->client->followRedirect();   // redirection vers /html/projet
            $this->assertTrue($crawler->filter('#yourproject')->count() > 0);

       } /* Fin de testIdInconnu */


       /**
        * Test d'un cas : un utilisateur supprime le projet d'un autre
        */
       public function testMauvaisUtilisateur() {
           // C'est u1 qui est connecté. Il faut donc crée un projet pour un utilisateur u2 sachant qu'u2 n'existe pas ...
           
        $container = $this->client->getContainer();
        $mngUser = $container->get("dvb.mng_user");
        $u2 = $mngUser->getNew();
        $u2->setUserName("u2"); $u2->setUsernameCanonical("u2"); $u2->setEmail("u2@test.com"); $u2->setEmailCanonical("u2@test.com"); $u2->setPassword("pass");
        $mngUser->persist($u2);
        $mngProjet = $container->get("dvb.mng_projet");
        $p1a =  $mngProjet->getNew();
        $p1a->setLibelle('p2a'); 
        $p1a->setUser($u2); 
        $mngProjet->persist($p1a);

        $crawler = $this->client->request('POST','/projet/destroy_confirmed', array("form" => array("id" => "" . $p1a->getId())));
        $crawler = $this->client->followRedirect();   // redirection vers /html/projet
        $this->assertTrue($crawler->filter('#yourproject')->count() > 0);
        $this->assertTrue($crawler->filter('html:contains("site.project.wronguser")')->count() > 0);
        $obj = $mngProjet->findOneById($p1a->getId());
        $this->assertNotNull($obj);

       } /* Fin de testMauvaisUtilisateur */
}

