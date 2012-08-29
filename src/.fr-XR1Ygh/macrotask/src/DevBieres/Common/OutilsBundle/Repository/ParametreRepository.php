<?php
namespace DevBieres\Common\OutilsBundle\Repository;

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

class ParametreRepository extends EntityRepository 
{

  /**
   * Retourne un élément du paramétrage par son code
   * @param string $code le code recherche
   * Appel une autre méthode qui effectue le travail par date
   */
  public function findOneByCode($code) {
      return $this->findOneByCodeAndDate($code, new \DateTime());
  } // Fin de findOneByCode

  /**
   * Retourne un élément par son code et la date de validite
   * @param string $code le code recherché
   * @param DateTime $date une date pour la période effective du paramètre
   */
  public function findOneByCodeAndDate($code, $date) {

    // -1-
    $q = $this->getEntityManager()
              ->createQuery("SELECT p FROM DevBieresCommonOutilsBundle:Parametre p
                               WHERE p.code = :code
                               AND :date BETWEEN p.debut AND p.fin")
              ->setParameter('code', $code)
              ->setParameter('date', $date);

    // -2-
    try {
      return $q->getSingleResult();
    } catch(\Doctrine\ORM\NoResultException $e) { return null; }

  } // Fin de findOneByCodeAndDate


}
