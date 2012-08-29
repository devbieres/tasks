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
use DevBieres\Common\BaseBundle\Controller\SecuredController;

use Symfony\Component\HttpFoundation\Request;

class PreferenceController extends BaseController 
{

     /**
      * Edit les préférences de l'utilisateur
      */
     public function editAction(Request $request) {
        // -1-
        $obj = $this->manageUnconnectedUser(); if($obj != null) { return $obj; }

        // -2-
        $up = $this->getUserManager()->getUserPreference($this->getUser());

        // -3-
        $form = $this->createForm( $this->getUserManager()->getUserPreferenceType(), $up);

        // -4-
        if($request->getMethod() == "POST") {
            $form->bindRequest($request);
            if($form->isValid()) {
                
                $this->getUserManager()->persist($up);

                // Mise à jour de la locale utilisateur
                $this->setLocale($up->getLocale());

                //
                $this->storeFlash($this->trans('site.user.preference.updated'));
            }
        }

        // -5-
        return $this->render( $this->getViewPath('Preference:edit'),
                       array("form" => $form->createView())
                      );
     } // fin de l'action edit

}
