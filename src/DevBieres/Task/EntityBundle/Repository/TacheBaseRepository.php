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

abstract class TacheBaseRepository extends EntityRepository 
{

  /**
   * Permet de spécialiser les traitements
   */
  abstract protected function getMainEntityName();

   /**
    * Retourne toutes les tâches associés du projet en fonction d'un etat (par defaut A_FAIRE)
    * @param $projet Projet Le projet
    * @param $etat Int Etat du projet (par defaut : ETAT_AFAIRE)
    */
   public function findByProjet($projet, $etat = TacheBase::ETAT_AFAIRE) {

     // -1-
     $str = sprintf("SELECT ts,p FROM DevBieresTaskEntityBundle:%s ts
               INNER JOIN ts.priorite p
               WHERE ts.projet = :projet
               AND ts.etat = :etat
               ORDER BY p.niveau DESC, ts.updatedAt DESC", $this->getMainEntityName());

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
     $str = sprintf("DELETE FROM DevBieresTaskEntityBundle:%s ts
               WHERE ts.projet = :projet", $this->getMainEntityName());

     // -2-
     return $this->getEntityManager()->createQuery($str)
       ->setParameter('projet', $projet)
       ->execute();

   } // deleteByProjet


   /**
    * Recherche la prochaine tache en fonction des paramètres
    * @param User $user l'utilisateur (obligatoire)
    * @param int $ordre une date de filtre
    * @param int $etat l'état de la tache
    */
   public function findNextPrevious($user, $ordre,  $etat = TacheBase::ETAT_AFAIRE, $sens = ">") {

     // -1-
     $q = $this->createQueryBuilder('ts')
       ->leftjoin('ts.priorite', 'p')
       ->leftjoin('ts.projet', 'pt')
       ->where('pt.user = :user')
       ->andWhere('ts.etat = :etat')
       ->andWhere(sprintf('ts.ordre %s :ordre',$sens))
       ->setParameter('user', $user)
       ->setParameter('etat', $etat)
       ->setParameter('ordre', $ordre);


     // -2-
     $q->orderBy('ts.ordre', 'ASC');
     $q->setMaxResults(1);

     // -3-
     try {
       return $q->getQuery()->getSingleResult();
     }
     catch (\Doctrine\ORM\NoResultException $e) {
       return null;
     } // Fin de -5-

   }


   /**
    * Retourne les tâches pour un utilisateur
    * @param $user User l'utilisateur
    * @param $filtre string un filtre de recherche
    * @param $etat integer l'etat recherché (par défaut = ETAT_AFAIRE)
    */
   public function findByUser($user, $filtre = '', $etat = TacheBase::ETAT_AFAIRE) {

     // -0-
     /*
      * $config = $this->getEntityManager()->getConfiguration();
     $config->addCustomStringFunction('IFNULL', 'DoctrineExtensions\Query\Mysql\IfNull');
     $config->addCustomNumericFunction('DEGREES', 'DoctrineExtensions\Query\Mysql\Degrees');
      */

     // -1-
     // Le mot clé HIDDEN doit permettre de faire un calcul sans que la valeur ne soit mappée
     $str = sprintf("SELECT ts, p, pt, IFNULL(ts.planif,'31/12/2999') AS HIDDEN planif_null  FROM DevBieresTaskEntityBundle:%s ts
                INNER JOIN ts.priorite p
                INNER JOIN ts.projet pt
                WHERE pt.user = :user",
                 $this->getMainEntityName());

     // -2- Gestion d'un filtre ?
     if($etat != -1) { $str .= "  AND ts.etat = :etat"; }
     if(strlen($filtre) > 0) {
             $str .= " AND (ts.libelle like :filtre OR pt.libelle like :filtre)";
     }

     // -3- Gestion d'un ordre
     $str .= " ORDER BY planif_null ASC, p.niveau desc, ts.createdAt DESC, ts.etat ASC";

     // -4-
     $q = $this->getEntityManager()->createQuery($str);
                 $q->setParameter('user', $user);

     // -5-
     if($etat != -1) { $q->setParameter('etat', $etat); }
     if(strlen($filtre) > 0) {
         $q = $q->setParameter('filtre', '%' . $filtre . '%');
     }

     // -6-
     return $q->execute();
   }

   /**
    * Retourne les tâches pour un utilisateur
    * @param $user User l'utilisateur
    */
   public function findTrashByUser($user, $date, $filtre = '') {

     // -1-
     $str = sprintf("SELECT ts, p, pt FROM DevBieresTaskEntityBundle:%s ts
                INNER JOIN ts.priorite p
                INNER JOIN ts.projet pt
                WHERE pt.user = :user
                AND ts.misALaCorbeille <= :date
                AND (ts.etat = :etat1 OR ts.etat = :etat2) ", $this->getMainEntityName());


     // -2- Gestion d'un filtre ?
     if(strlen($filtre) > 0) {
             $str .= " AND (ts.libelle like :filtre OR pt.libelle like :filtre)";
     }

     // -3- Tri
     $str .= " ORDER BY ts.planif ASC, p.niveau desc,  ts.createdAt DESC, ts.etat ASC";

     // -4-
     $q = $this->getEntityManager()->createQuery($str)
                ->setParameter('user', $user)
                ->setParameter('etat1', TacheBase::ETAT_ANNULE)
                ->setParameter('etat2', TacheBase::ETAT_FAIT)
                ->setParameter('date', $date); 

     // -5-
     if(strlen($filtre) > 0) {
         $q = $q->setParameter('filtre', '%' . $filtre . '%');
     }

     // -6-
     return $q->execute();

   } // Fin de findAllByUser

   


}
