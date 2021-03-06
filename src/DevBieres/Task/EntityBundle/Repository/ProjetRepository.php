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

class ProjetRepository extends EntityRepository 
{

  /**
   * Retourne les projets de l'utilisateur
   * @param $user DevBieres\Task\UserBundle\Entity\User l'utilisateur
   */
  public function findByUser($user) {

        return $this->findByUserQuery($user)->getQuery()->execute(); //getResult();
  }

  /**
   * Retourne la query associé à la recherche pour un utilisateur
   * @param $user l'utilisateur
   */
  public function findByUserQuery($user) {

    // -1-
    $q = $this->createQueryBuilder('p');

    // -2-
    $q->where('p.user = :user');
    $q->orderBy('p.code');

    // -3-
    $q->setParameter('user', $user);

    // -4-
    return $q;

  } /* findByUserQuery */


  /**
   * Retourne un projet par code pour l'utilisateur
   * @param $user l'utilisateur
   * @param $code le code
   * */
  public function findOneByUserAndCode($user, $code) {
    // -1-
    $q = $this->createQueryBuilder('p');

    // -2-
    $q->where('p.user = :user');
    $q->andWhere(' p.code = :code ');
    $q->orderBy('p.code');

    // -3-
    $q->setParameter('user', $user);
    $q->setParameter('code', $code);

     // -4-
     try {
       return $q->getQuery()->getSingleResult();
     }
     catch (\Doctrine\ORM\NoResultException $e) {
       return null;
     } // Fin de -5-

  } // Fin de findOneByCode

  /**
   * Retourne un projet par code pour l'utilisateur
   * @param $user l'utilisateur
   * @param $code le code
   * */
  public function findOneByUserAndRaccourci($user, $code) {
    // -1-
    $q = $this->createQueryBuilder('p');

    // -2-
    $q->where('p.user = :user');
    $q->andWhere(' p.raccourci = :code ');
    $q->orderBy('p.code');

    // -3-
    $q->setParameter('user', $user);
    $q->setParameter('code', $code);

     // -4-
     try {
       return $q->getQuery()->getSingleResult();
     }
     catch (\Doctrine\ORM\NoResultException $e) {
       return null;
     } // Fin de -5-

  } // Fin de findOneByCode


}
