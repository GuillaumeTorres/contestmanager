<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializationContext;



/**
 * Config
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="CoreBundle\Entity\RoleRepository")
 */
class Config
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="room_number", type="integer", nullable=true)
     */
    private $roomNumber;

    /**
     * @var integer
     *
     * @ORM\Column(name="level_max", type="integer", nullable=true)
     */
    private $level_max;

    /**
     * @ORM\ManyToMany(targetEntity="CoreBundle\Entity\Role", cascade={"persist"})
     */
    private $roles;

    public function __toString()
    {
        return $this->name;
    }


    /**
     * Add roles
     *
     * @param \CoreBundle\Entity\Role $roles
     *
     * @return Role
     */
    public function addRoles(\CoreBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;

        return $this->roles;
    }

    /**
     * Remove roles
     *
     * @param \CoreBundle\Entity\Role $roles
     */
    public function removeRoles(\CoreBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * Get roles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set roles
     *
     * @param Role $roles
     *
     * @return Role
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this->roles;
    }

    /**
     * @return int
     */
    public function getLevelMax()
    {
        return $this->level_max;
    }

    /**
     * @param int $level_max
     */
    public function setLevelMax($level_max)
    {
        $this->level_max = $level_max;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set roomNumber
     *
     * @param integer $roomNumber
     *
     * @return Config
     */
    public function setRoomNumber($roomNumber)
    {
        $this->roomNumber = $roomNumber;

        return $this;
    }

    /**
     * Get roomNumber
     *
     * @return integer
     */
    public function getRoomNumber()
    {
        return $this->roomNumber;
    }
    

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Config
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


}
