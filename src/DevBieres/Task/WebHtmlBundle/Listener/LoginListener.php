<?php

namespace DevBieres\Task\WebHtmlBundle\Listener;


/*
 * ----------------------------------------------------------------------------
 * « LICENCE BEERWARE » (Révision 42):
 * <thierry<at>lafamillebn<point>net> a créé ce fichier. Tant que vous conservez cet avertissement,
 * vous pouvez faire ce que vous voulez de ce truc. Si on se rencontre un jour et
 * que vous pensez que ce truc vaut le coup, vous pouvez me payer une bière en
 * retour. 
 * ----------------------------------------------------------------------------
 * ----------------------------------------------------------------------------
 * "THE BEER-WARE LICENSE" (Revision 42):
 * <thierry<at>lafamillebn<point>net> wrote this file. As long as you retain this notice you
 * can do whatever you want with this stuff. If we meet some day, and you think
 * this stuff is worth it, you can buy me a beer in return. 
 * ----------------------------------------------------------------------------
 * Plus d'infos : http://fr.wikipedia.org/wiki/Beerware
 * ----------------------------------------------------------------------------
*/

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Custom login listener
 * Très fortement inspiré de : http://www.metod.si/login-event-listener-in-symfony2/
 */
class LoginListener {

   private $securityContext;

   private $dispatcher;

   private $session;

   private $mng_user;

   private $mng_tache;

   private $router;


  public function __construct(Router $router, SecurityContext $context, EventDispatcher $dispatcher, $session, $mng_user, $mng_tache)
	{
		$this->securityContext = $context;
    $this->session = $session;
    $this->mng_user = $mng_user;
    $this->mng_tache = $mng_tache;
    // Ajout du router
    $this->router = $router;
    $this->dispatcher = $dispatcher;
	}

  public function onSecurityInteractiveLogin(Event $event)
	{
		$user = $this->securityContext->getToken()->getUser();

		// -1- Gestion de la langue
    $up = $this->mng_user->getUserPreference($user);
    $this->session->set('_locale', $up->getLocale());

    // -2- Nettoyage de la corbeille (fait également au logout ..)
    $this->mng_tache->clearTrash($user, $up->getNbJours());

    // -3-
    $detector = new \Mobile_Detect();
    if($detector->isMobile() || $detector->isTablet()) {
         $this->dispatcher->addListener(KernelEvents::RESPONSE, array($this, 'onKernelResponse'));
    }

  } // Fin de OnSecurityContext

  public function onKernelResponse(FilterResponseEvent $event) {
       $route = "mobile_home"; 
       $event->setResponse(new RedirectResponse($this->router->generate($route))); 
  } // Fin de onKernelResponse

}
