<?php
namespace DevBieres\Task\EntityBundle\Entity;
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

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
//use Symfony\Component\Validator\ExecutionContext;
use JMS\SerializerBundle\Annotation\Expose;

use DevBieres\Common\BaseBundle\Entity\CodeLibelleBase;

/**
 * Définit les niveaux de priorité associée aux tâches
 * @ORM\Entity(repositoryClass="DevBieres\Task\EntityBundle\Repository\PrioriteRepository")
 * @ORM\Table(name="dvb_task_priorite")
 */
class Priorite extends CodeLibelleBase {

  /**
   * Niveau : permet d'ordonnancer les tâches en fonction d'un niveau de priorité
   * @ORM\Column(type="integer", unique=true)
   * @Assert\NotNull
   * @Assert\Min(0)
   * @Expose
   */
  protected $niveau;
  public function getNiveau() { return $this->niveau; }
  public function setNiveau($value) { $this->niveau = $value; }

}

