<?php

namespace UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\MaxDepth;
use TeamBundle\Entity\Team;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @ExclusionPolicy("all")
 * @ORM\AttributeOverrides({
        @ORM\AttributeOverride(
 *              name="salt",
 *              column=@ORM\Column(name="salt", type="string", nullable=true)
 *          )
 *     })
 */
class User extends BaseUser implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    protected $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     * @Expose
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     * @Expose
     */
    private $lastName;

    /**
     * @ORM\ManyToOne(targetEntity="SchoolBundle\Entity\School", inversedBy="teacher", cascade={"persist"})
     * @ORM\JoinColumn(name="school_id", referencedColumnName="id", onDelete="set null")
     * @Expose
     * @MaxDepth(1)
    */
    private $school;

    /**
     * @ORM\OneToMany(targetEntity="MatchBundle\Entity\GroupMatch", mappedBy="teacher", orphanRemoval=true)
     */
    private $group;

    public function __construct()
    {
        parent::__construct();
        $this->salt = null;
        $this->group = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'email' => $this->getEmail(),
            'roles' => $this->getRoles(),
        ];
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set school
     *
     * @param \SchoolBundle\Entity\School $school
     *
     * @return User
     */
    public function setSchool(\SchoolBundle\Entity\School $school = null)
    {
        $this->school = $school;

        return $this;
    }

    /**
     * Get school
     *
     * @return \SchoolBundle\Entity\School
     */
    public function getSchool()
    {
        return $this->school;
    }

    /**
     * Set team
     *
     * @param string $team
     *
     * @return User
     */
    public function setTeam($team)
    {
        $this->team = $team;

        return $this;
    }

    /**
     * Get group
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroup()
    {
        return $this->group;
    }
}
