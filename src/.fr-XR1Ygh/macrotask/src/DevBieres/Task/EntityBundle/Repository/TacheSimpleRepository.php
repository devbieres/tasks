<?php
namespace DevBieres\Task\EntityBundle\Repository;

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

use Doctrine\ORM\EntityRepository;
use DevBieres\Task\EntityBundle\Entity\TacheBase;

class TacheSimpleRepository extends EntityRepository 
{

   /**
    * Retourne toutes les tâches associés du projet en fonction d'un etat (par defaut A_FAIRE)
    * @param $projet Projet Le projet
    * @param $etat Int Etat du projet (par defaut : ETAT_AFAIRE)
    */
   public function findByProjet($projet, $etat = TacheBase::ETAT_AFAIRE) {

     // -1-
     $str = "SELECT ts,p FROM DevBieresTaskEntityBundle:TacheSimple ts
               INNER JOIN ts.priorite p
               WHERE ts.projet = :projet
               AND ts.etat = :etat
               ORDER BY p.niveau DESC, ts.updatedAt DESC";

     // -2-
     return $this->getEntityManager()->createQuery($str)
       ->setParameter('projet', $projet)
       ->setParameter('etat', $etat)
       ->execute();

   }// Fin de findAllByProject

   /**
    * Suppression des tâches d'un projet
    * @param $projet Projet le projet
    */
   public function deleteByProjet($projet) {
     // -1-
     $str = "DELETE FROM DevBieresTaskEntityBundle:TacheSimple ts
               WHERE ts.projet = :projet";

     // -2-
     return $this->getEntityManager()->createQuery($str)
       ->setParameter('projet', $projet)
       ->execute();

   } // deleteByProjet


   /**
    * Retourne les tâches pour un utilisateur
    * @param $user User l'utilisateur
    * @param $etat integer l'etat recherché (par défaut = ETAT_AFAIRE)
    */
   public function findByUser($user, $etat = TacheBase::ETAT_AFAIRE) {

     // -1-
     $str = "SELECT ts, p, pt FROM DevBieresTaskEntityBundle:TacheSimple ts
                INNER JOIN ts.priorite p
                INNER JOIN ts.projet pt
                WHERE pt.user = :user
                AND ts.etat = :etat
                ORDER BY p.niveau DESC, ts.updatedAt DESC";

     // -2-
     return $this->getEntityManager()->createQuery($str)
                 ->setParameter('user', $user)
                 ->setParameter('etat', $etat)
                 ->execute();

   } // Fin de findAllByUser


}
