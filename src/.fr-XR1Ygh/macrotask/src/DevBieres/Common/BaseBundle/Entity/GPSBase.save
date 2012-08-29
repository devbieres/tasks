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

use JMS\SerializerBundle\Annotation\Expose;
use JMS\SerializerBundle\Annotation\Exclude;

/**
 * Liste les caractéristiques d'une entité ayant des coordonnées GPS
 * @ORM\MappedSuperClass
 */
abstract class GPSBase extends CodeLibelleBase  
{

    /**
     * Latitude
     * @ORM\Column(type="decimal", scale=15, precision=20)
     */
    protected $latitude = 0;
    public function getLatitude() { return $this->latitude; }
    public function setLatitude($value) { $this->latitude = $value; }

   /**
    * Longitude
    * @ORM\Column(type="decimal", scale=15, precision=20)
    */
    protected $longitude = 0;
    public function getLongitude() { return $this->longitude; }
    public function setLongitude($value) { $this->longitude = $value; }

    /**
     * KML String
     */
    public function toKMLString() {
       return sprintf("%s,%s,0", $this->getLongitude(), $this->getLatitude()); 
    }     

} // Fin de CodeLibelleBase
