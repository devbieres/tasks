<?php
namespace DevBieres\Common\BaseBundle\Entity;
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
use Doctrine\Common\Collections\ArrayCollection;

use JMS\SerializerBundle\Annotation\ExclusionPolicy;
use JMS\SerializerBundle\Annotation\Expose;
use JMS\SerializerBundle\Annotation\Exclude;

/**
 * Entité de base pour les certaines entités utilisées dans l'application
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 * @ExclusionPolicy("all")
 */
abstract class EntityBase 
{
   /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue(strategy="AUTO")
    * @Expose
    */
   protected $id;
   public function getId() { return $this->id; }

   /**
    * @ORM\Column(type="datetime")
    */
   protected $createdAt;
   public function getCreatedAt() { return $this->createdAt; }

   /**
    * @ORM\Column(type="datetime")
    */
   protected $updatedAt;
   public function getUpdatedAt() { return $this->updatedAt; }


   /**
    * @ORM\PrePersist
    */
   public function prePersist()
   {
       $this->createdAt = new \DateTime(date("Y-m-d H:i:s"));
       $this->updatedAt = $this->createdAt; 
   }

   /**
    * @ORM\PreUpdate
    */
   public function preUpdate()
   {
       $this->updatedAt = new \DateTime(date("Y-m-d H:i:s")); 
   }

} // Fin de Base
