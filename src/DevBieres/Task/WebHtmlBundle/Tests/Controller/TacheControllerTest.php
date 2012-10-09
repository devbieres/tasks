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

class TacheControllerTest extends ConnecteControllerTest {

      protected $basse;
      protected $normale;
      protected $haute;
   
     protected $p1;
     protected $p2;
     protected $p3;

     protected $tu2;


      public function setup() {
          // -1- Creation des infos parents
          parent::setup();

          // -2- récupération de l'utilisateur
          $container = $this->client->getContainer();
          $mng = $container->get("dvb.mng_user");
          $u1 = $mng->findOneByUsername('test');
          $u2 = $mng->getNew();
          $u2->setUserName("u2"); $u2->setUsernameCanonical("u2"); $u2->setEmail("u2@test.com"); $u2->setEmailCanonical("u2@test.com"); $u2->setPassword("pass");
          $mng->persist($u2);

          // -3- Création des priorités
          $mng = $container->get("dvb.mng_priorite");
          $obj = $mng->getNew();
          $obj->setNiveau(0); $obj->setLibelle('Basse'); $mng->persist($obj);
          $this->basse = $obj;
          $obj = $mng->getNew();
          $obj->setNiveau(1); $obj->setLibelle('Normal'); $mng->persist($obj);
          $this->normale = $obj;
          $obj = $mng->getNew();
          $obj->setNiveau(2); $obj->setLibelle('Haute'); $mng->persist($obj);
          $this->haute = $obj;

          // -4- Creation de trois projets associés à l'utilisateur
          $mng = $container->get("dvb.mng_projet");
          $obj = $mng->getNew();
          $obj->setLibelle("Test Projet A"); $obj->setUser($u1); $mng->persist($obj);
          $this->p1 = $obj;
          $obj = $mng->getNew();
          $obj->setLibelle("Test Projet B"); $obj->setUser($u1); $mng->persist($obj);
          $this->p2 = $obj;
          $obj = $mng->getNew();
          $obj->setLibelle("Test Projet C"); $obj->setUser($u1); $mng->persist($obj);
          $this->p3 = $obj;
          $obj = $mng->getNew();
          $obj->setLibelle("U2 Projet 1"); $obj->setUser($u2); $mng->persist($obj);
          $obj = $mng->getNew();
          $obj->setLibelle("U2 Projet 2"); $obj->setUser($u2); $mng->persist($obj);

          // -5- Pour l'utilisateur 2 on crée un tache
          $mng = $container->get("dvb.mng_tachesimple");
          $t = $mng->getNew();
          $t->setLibelle("test"); $t->setProjet($obj); $t->setPriorite($this->basse); 
          $mng->persist($t);
          $this->tu2 = $t;

          

      } /* Fin du set up */

      public function testCheckUser() {

            // -4-
            $crawler = $this->client->request('GET','/tache/destroy/' . $this->tu2->getId());
            // Lutilisateur n'a pas de projet, il est donc redirigé vers la page d'accueil
            $crawler = $this->client->followRedirect();  
            $this->assertTrue($crawler->filter('#yourtasks')->count() > 0);
            $this->assertTrue($crawler->filter('li.item')->count() == 0);
            $this->assertTrue($crawler->filter('.flash ul li')->count() == 1);

      }
 
       /**
        * Test de la page A propos
        */
       public function testSimple() {

            // -1-
            $crawler = $this->client->request('GET','/');
            $this->assertTrue($crawler->filter('#yourtasks')->count() > 0);
            $this->assertTrue($crawler->filter('li.item')->count() == 0);
            $this->assertTrue($crawler->filter('li.item_titre')->count() == 0);

            // -2- Appel du lien pour création d'une tâche
            $link = $crawler->filter("#nav_newtask")->eq(0)->link();
            $crawler = $this->client->click($link);
            $this->assertTrue($crawler->filter('#newtask')->count() > 0);
           
            // -3-  
            $form = $crawler->selectButton('submit')->form(array(
                   'TacheSimple[libelle]' => 'tache 1',
                   'TacheSimple[priorite]' => $this->haute->getId(),
                   'TacheSimple[projet]' => $this->p1->getId()));
            $this->client->submit($form);
            $crawler = $this->client->followRedirect();  
            $this->assertTrue($crawler->filter('#yourtasks')->count() > 0);
            $this->assertTrue($crawler->filter('li.item')->count() == 1);
            $this->assertTrue($crawler->filter('li.item_titre')->count() == 1);
            $this->assertTrue($crawler->filter('html:contains("tache 1")')->count() > 0);
            $this->assertTrue($crawler->filter('html:contains("Test Projet A")')->count() > 0);
            $this->assertTrue($crawler->filter('html:contains("NON PLAN")')->count() > 0);

            // -4- Recherche du lien d'édition
            $link = $crawler->filter("div.item_action a")->eq(0)->link();
            $crawler = $this->client->click($link);
            $this->assertTrue($crawler->filter('#edittask')->count() > 0);

            // -5- Mise à jour
            $form = $crawler->selectButton('submit')->form(array(
                   'TacheSimple[libelle]' => 'tache 1 mise a jour',
                   'TacheSimple[priorite]' => $this->basse->getId(),
                   'TacheSimple[projet]' => $this->p2->getId()));
            $this->client->submit($form);
            $crawler = $this->client->followRedirect();  
            $this->assertTrue($crawler->filter('#yourtasks')->count() > 0);
            $this->assertTrue($crawler->filter('li.item')->count() == 1);
            $this->assertTrue($crawler->filter('li.item_titre')->count() == 1);
            $this->assertTrue($crawler->filter('html:contains("tache 1 mise a jour")')->count() > 0);
            $this->assertTrue($crawler->filter('html:contains("Test Projet B")')->count() > 0);
            //$this->assertTrue($crawler->filter('html:contains("BASSE")')->count() > 0);

            // -6- Test du passage au fait
            $link = $crawler->filter("div.item_action a")->eq(1)->link();
            $crawler = $this->client->click($link);
            $crawler = $this->client->followRedirect();  
            // --> l'utilisateur reste sur la page principale
            $this->assertTrue($crawler->filter('li.item')->count() == 0);
            $this->assertTrue($crawler->filter('li.item_titre')->count() == 0);
            $this->assertTrue($crawler->filter('html:contains("tache 1 mise a jour")')->count() == 0);
            $this->assertTrue($crawler->filter('html:contains("Test Projet B")')->count() == 0);
            //$this->assertTrue($crawler->filter('html:contains("BASSE")')->count() == 0);

            // -7- Access à la corbeille 
            $link = $crawler->filter("#nav_trash")->eq(0)->link();
            $crawler = $this->client->click($link);
            $this->assertTrue($crawler->filter('#yourtrash')->count() > 0);
            $this->assertTrue($crawler->filter('li.item')->count() == 1);
            $this->assertTrue($crawler->filter('li.fait')->count() == 1);
            //$this->assertTrue($crawler->filter('li.item_titre')->count() == 1);
            $this->assertTrue($crawler->filter('html:contains("tache 1 mise a jour")')->count() > 0);
            $this->assertTrue($crawler->filter('html:contains("Test Projet B")')->count() > 0);
            //$this->assertTrue($crawler->filter('html:contains("BASSE")')->count() > 0);

            // -8- Re Activation de la tache
            $link = $crawler->filter("div.item_action a")->eq(0)->link();
            $crawler = $this->client->click($link);
            $crawler = $this->client->followRedirect(); // Ici on est directement redirigé vers la page list des actions
            $this->assertTrue($crawler->filter('#yourtasks')->count() > 0);
            $this->assertTrue($crawler->filter('li.item')->count() == 1);
            $this->assertTrue($crawler->filter('li.item_titre')->count() == 1);
            $this->assertTrue($crawler->filter('html:contains("tache 1 mise a jour")')->count() > 0);
            $this->assertTrue($crawler->filter('html:contains("Test Projet B")')->count() > 0);
            //$this->assertTrue($crawler->filter('html:contains("BASSE")')->count() > 0);

            // -9- Test du passage au fait mais avec suite
            $link = $crawler->filter("div.item_action a")->eq(2)->link();
            $crawler = $this->client->click($link);
            // --> l'utilisateur doit être redirigé vers la page de création de tâche
            $this->assertTrue($crawler->filter('#newtask')->count() > 0);

            // -10- Access à la corbeille 
            $link = $crawler->filter("#nav_trash")->eq(0)->link();
            $crawler = $this->client->click($link);
            $this->assertTrue($crawler->filter('#yourtrash')->count() > 0);
            $this->assertTrue($crawler->filter('li.item')->count() == 1);
            //$this->assertTrue($crawler->filter('li.item_titre')->count() == 1);
            $this->assertTrue($crawler->filter('li.fait')->count() == 1);
            $this->assertTrue($crawler->filter('li.annule')->count() == 0);
            $this->assertTrue($crawler->filter('html:contains("tache 1 mise a jour")')->count() > 0);
            $this->assertTrue($crawler->filter('html:contains("Test Projet B")')->count() > 0);
            //$this->assertTrue($crawler->filter('html:contains("BASSE")')->count() > 0);

            // -11- Re Activation de la tache
            $link = $crawler->filter("div.item_action a")->eq(0)->link();
            $crawler = $this->client->click($link);
            $crawler = $this->client->followRedirect(); // Ici on est directement redirigé vers la page list des actions
            $this->assertTrue($crawler->filter('#yourtasks')->count() > 0);

            // -12- Test du passage au fait
            $link = $crawler->filter("div.item_action a")->eq(3)->link();
            $crawler = $this->client->click($link);
            $crawler = $this->client->followRedirect();  
            // --> l'utilisateur reste sur la page principale
            $this->assertTrue($crawler->filter('li.item')->count() == 0);
            $this->assertTrue($crawler->filter('li.item_titre')->count() == 0);
            $this->assertTrue($crawler->filter('html:contains("tache 1 mise a jour")')->count() == 0);
            $this->assertTrue($crawler->filter('html:contains("Test Projet B")')->count() == 0);

            // -13- Access à la corbeille 
            $link = $crawler->filter("#nav_trash")->eq(0)->link();
            $crawler = $this->client->click($link);
            $this->assertTrue($crawler->filter('#yourtrash')->count() > 0);
            $this->assertTrue($crawler->filter('li.fait')->count() == 0);
            $this->assertTrue($crawler->filter('li.annule')->count() == 1);

            // -14- Suppression definitive de la tache
            $link = $crawler->filter("div.item_action a")->eq(1)->link();
            $crawler = $this->client->click($link);
            $crawler = $this->client->followRedirect(); // Ici on est redirigé vers la page d'accueil
            $this->assertTrue($crawler->filter('#yourtrash')->count() > 0);
            $this->assertTrue($crawler->filter('li.item')->count() == 0);

            // -15-
            $crawler = $this->client->request('GET','/');
            $this->assertTrue($crawler->filter('#yourtasks')->count() > 0);
            $this->assertTrue($crawler->filter('li.item')->count() == 0);
            $this->assertTrue($crawler->filter('li.item_titre')->count() == 0);

       } /* Fin du test simple */

       public function testSomeNew() {

            // -1-
            $crawler = $this->client->request('GET','/');
            $this->assertTrue($crawler->filter('#yourtasks')->count() > 0);
            $this->assertTrue($crawler->filter('li.item')->count() == 0);

            // -2-
            $link = $crawler->filter("#nav_newmultitask")->eq(0)->link();
            $crawler = $this->client->click($link);
            $this->assertTrue($crawler->filter('#explication')->count() > 0);
            $this->assertTrue($crawler->filter('#somenewtask')->count() > 0);

            // -3-
            $form = $crawler->selectButton('submit')->form(array(
                   'MultiTacheSimple[contenu]' => "+tache haute\n tache normale\n-tache basse"));
            $this->client->submit($form);
            $crawler = $this->client->followRedirect(); // Ici on est redirigé vers la page d'accueil

            // -4-
            $this->assertTrue($crawler->filter('#yourtasks')->count() > 0);
            $this->assertTrue($crawler->filter('li.item')->count() == 3);
            //$this->assertTrue($crawler->filter('li.item_titre')->count() == 3);

            // ==> On en profite pour mettre toutes les tâches à la corbeille pour faire un supprimer tout
            for($i = 0; $i < 3; $i++) {
                $link = $crawler->filter("div.item_action a")->eq(1)->link();
                $crawler = $this->client->click($link);
                $crawler = $this->client->followRedirect(); // Ici on est redirigé vers la page d'accueil
                $this->assertTrue($crawler->filter('#yourtasks')->count() > 0);
                $this->assertTrue($crawler->filter('li.item')->count() == (2-$i));
            }
            
            
            // -5- Access à la corbeille 
            $link = $crawler->filter("#nav_trash")->eq(0)->link();
            $crawler = $this->client->click($link);
            $this->assertTrue($crawler->filter('#yourtrash')->count() > 0);
            $this->assertTrue($crawler->filter('li.fait')->count() == 3);
  
            // -6-
            $link = $crawler->filter("#trash_emptyall")->eq(0)->link();
            $crawler = $this->client->click($link);
            $this->assertTrue($crawler->filter('#emptyall')->count() > 0);

            // -7- Récupération du formulaire
            $form = $crawler->selectButton('submit')->form();
            $this->client->submit($form);
            $crawler = $this->client->followRedirect(); // Ici on est redirigé vers la page d'accueil


            // -8- Access à la corbeille 
            $link = $crawler->filter("#nav_trash")->eq(0)->link();
            $crawler = $this->client->click($link);
            $this->assertTrue($crawler->filter('#yourtrash')->count() > 0);
            $this->assertTrue($crawler->filter('li.fait')->count() == 0);

       } // testSomeNew


} // Fin de TacheControllerTest

