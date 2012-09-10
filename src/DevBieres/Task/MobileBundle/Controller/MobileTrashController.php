<?php
namespace DevBieres\Task\MobileBundle\Controller;
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
use Doctrine\ORM\EntityRepository;
use DevBieres\Common\BaseBundle\Controller\SecuredController;

use Symfony\Component\HttpFoundation\Request;
use DevBieres\Task\WebHtmlBundle\Controller\TrashController;

class MobileTrashController extends TrashController 
{

  protected function getViewPath($name) { return sprintf("DevBieresTaskMobileBundle:%s.html.twig", $name); }

  /**
   * Spécialisation de redirectToHome
   */
  protected function redirectToHome() { return $this->redirect($this->generateUrl('mobile_home')); }
  /**
   * Spécialisation de redirectToTrash
   */
  protected function redirectToTrash() { return $this->redirect($this->generateUrl('mobile_trash')); }


}

?>
