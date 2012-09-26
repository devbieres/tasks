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

class DefaultControllerTest extends WebTestCase
{
    public function testIdentificationKO()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $crawler = $client->followRedirect(); // Normalement, il doit me rediriger vers /login
        $this->assertTrue($crawler->filter('html:contains("Mot de passe")')->count() > 0);

        // Remplissage du formulaire
       $form = $crawler->selectButton('_submit')->form(array(
               '_username'  => 'user',
               '_password'  => 'pa$$word',
               ));

        // "click" sur le boutton OK
        $client->submit($form);
        $crawler = $client->followRedirect(); // L'utilisateur n'existe pas donc on reste là ;)

        
        $this->assertTrue($crawler->filter('html:contains("Mot de passe")')->count() > 0);
        $this->assertTrue($crawler->filter('html:contains("Nom d\'utilisateur ou mot de passe incorrect")')->count() > 0);

    }

    /**
     * Test de création de compte
     */
    public function testCreationCompte() {

        // -1- Passage par la page login qui contient le lien vers register
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $crawler = $client->followRedirect(); // Normalement, il doit me rediriger vers /login
        $this->assertTrue($crawler->filter('html:contains("Mot de passe")')->count() > 0);

        // -2- Sélection et click sur le lien "créer un compte"
        $link = $crawler->filter("#creercompte")->eq(0)->link();
        $crawler = $client->click($link);
        $this->assertTrue($crawler->filter('html:contains("Mot de passe")')->count() > 0);
        $this->assertTrue($crawler->filter('html:contains("Créer un compte")')->count() > 0);

        // -3-
        $form = $crawler->selectButton('_submit')->form(array(
               'fos_user_registration_form[username]'                 => 'test',
               'fos_user_registration_form[email]'                    => 'test@test.fr',
               'fos_user_registration_form[plainPassword][first]'     => 'test',
               'fos_user_registration_form[plainPassword][second]'    => 'test',
               ));
        
        $client->submit($form);
        $crawler = $client->followRedirect();  

        $this->assertTrue($crawler->filter('html:contains("Félicitations test")')->count() > 0);

        // -4-
        $crawler = $client->request('GET', '/login');
        $this->assertTrue($crawler->filter('html:contains("Mot de passe")')->count() > 0);
        
         
        // Remplissage du formulaire
        $form = $crawler->selectButton('_submit')->form(array(
               '_username'  => 'test',
               '_password'  => 'test',
               ));

        // "click" sur le boutton OK
        $client->submit($form);
        $crawler = $client->followRedirect();  
        //$crawler = $client->followRedirect();  

        $this->assertTrue($crawler->filter('#yourtasks')->count() > 0);

        // -5- Navigation sur la suppression de compte
        $link = $crawler->filter("#user_destroy")->eq(0)->link();
        $crawler = $client->click($link);
        $this->assertTrue($crawler->filter('#destroyaccount')->count() > 0);

        // -6- Recherche du formulaire
        $form = $crawler->filter('input[type=submit]')->form();
        $client->submit($form);
        $crawler = $client->followRedirect();  // ==> logout
        $crawler = $client->followRedirect();  // ==> html
        $crawler = $client->followRedirect();  // ==> login
        //var_dump($crawler);
        $this->assertTrue($crawler->filter('html:contains("Mot de passe")')->count() > 0);
   
    }
}
