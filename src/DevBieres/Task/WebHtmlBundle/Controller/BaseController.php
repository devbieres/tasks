<?php
namespace DevBieres\Task\WebHtmlBundle\Controller;
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
use DevBieres\Common\BaseBundle\Controller\SecuredBaseController;

class BaseController extends SecuredBaseController
{

  protected function getViewPath($name) { return sprintf("DevBieresTaskWebHtmlBundle:%s.html.twig", $name); }

  protected function getTacheManager() { return $this->get('dvb.mng_tachesimple'); }
  protected function getProjetManager() { return $this->get('dvb.mng_projet'); }
  protected function getUserManager() { return $this->get('dvb.mng_user'); }

  /**
   * Recherche un filtre dans l'objet request
   */
  protected function getFiltre($request) {
     // -1-
     if($request->getMethod() == 'POST') {
       $form = $request->request->get('form');
       return $form['filtre'];
     } else { return ''; }
  } // Fin de getFiltre

  /**
   * Centralise le redirect vers la page principale
   * TODO : mettre cela dans le base controler de Common avec gestion d'un paramètre dans le config
   */
  protected function redirectToHome() {
    return $this->redirect($this->generateUrl('web_home'));
  }
}

?>
