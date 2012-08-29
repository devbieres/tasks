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

abstract class ConnecteControllerTest extends WebTestCase {

  protected $client;
     
  /**
   * Set Up
   */
  public function setup() {
    parent::setup();

        // -1- Creation du client
        $this->client = static::createClient();

        // -2-
        $this->tearDown();

        // -3- On crée un utilisateur
        $crawler = $this->client->request('GET', '/register');
        $crawler = $this->client->followRedirect();  
        $this->assertTrue($crawler->filter('html:contains("Mot de passe")')->count() > 0);
        $this->assertTrue($crawler->filter('html:contains("Créer un compte")')->count() > 0);
        $form = $crawler->selectButton('_submit')->form(array(
               'fos_user_registration_form[username]'                 => 'test',
               'fos_user_registration_form[email]'                    => 'test@test.fr',
               'fos_user_registration_form[plainPassword][first]'     => 'test',
               'fos_user_registration_form[plainPassword][second]'    => 'test',
               ));
        
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();  
        $this->assertTrue($crawler->filter('html:contains("Félicitations test")')->count() > 0);
        
        
        // -4-
        $crawler = $this->client->request('GET', '/login');
        $this->assertTrue($crawler->filter('html:contains("Mot de passe")')->count() > 0);
        $form = $crawler->selectButton('_submit')->form(array(
               '_username'  => 'test',
               '_password'  => 'test',
               ));
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();  

  } /* fin du setup */


  public function tearDown() {
        // -1-
        $container = $this->client->getContainer();

        // -2- On vide les traces
        $container->get("dvb.mng_tachesimple")->purge();
        $container->get("dvb.mng_projet")->purge();
        $container->get("dvb.mng_priorite")->purge();
        $container->get("dvb.mng_user")->purge();


  } /* tearDown */


}
