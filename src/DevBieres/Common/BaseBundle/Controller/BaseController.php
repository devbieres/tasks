<?php
namespace DevBieres\Common\BaseBundle\Controller;
/*
 * ----------------------------------------------------------------------------
 * « LICENCE BEERWARE » (Révision 42):
 * <nantesparcours@lafamillebn.net> a créé ce fichier. Tant que vous conservez cet avertissement,
 * vous pouvez faire ce que vous voulez de ce truc. Si on se rencontre un jour et
 * que vous pensez que ce truc vaut le coup, vous pouvez me payer une bière en
 * retour. 
 * ----------------------------------------------------------------------------
 * ----------------------------------------------------------------------------
 * "THE BEER-WARE LICENSE" (Revision 42):
 * <nantesparcours@lafamillebn.net> wrote this file. As long as you retain this notice you
 * can do whatever you want with this stuff. If we meet some day, and you think
 * this stuff is worth it, you can buy me a beer in return. 
 * ----------------------------------------------------------------------------
 * Plus d'infos : http://fr.wikipedia.org/wiki/Beerware
 * ----------------------------------------------------------------------------
*/

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller 
{

     /**
      * Retourne le log
      */
     protected function getLogger() { return $this->get("logger"); }

     /**
      * retourne la fonction de serialization
      */
     protected function getSerializer() { return $this->get("serializer"); }

     /**
      * Retourne la requete
      */
     //protected function getRequest() { return $this->get('request'); }

     /**
      * Retourne la session
      */
     protected function getSession() { return $this->get('session'); }

     /**
      * Retourne l'url d'origine
      */
     protected function getUrlSource() { return  $this->getRequest()->headers->get('referer'); }

     /**
      * Mise à jour de la langue de l'utilisateur
      */
     protected function setLocale($locale) {
        $this->getSession()->set('_locale', $locale);
     }

     /**
      * Raccourci vers le service de traduction
      */
     protected function trans($var) {
          return $this->get('translator')->trans($var);
     }

     /**
      * Centralisation d'une méthode de conservation de messages
      */
     protected function storeFlash($message) { 
         $this->getSession()->getFlashBag()->add('flash', $message);
     } 

}

?>
