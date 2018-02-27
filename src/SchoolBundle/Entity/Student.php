<?php

namespace SchoolBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Student
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Student
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
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $lastName;

    /**
     * @var integer
     *
     * @ORM\Column(name="level", type="integer", length=1)
     */
    private $level;

    /**
     * @ORM\ManyToOne(targetEntity="TeamBundle\Entity\Team", inversedBy="student")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id", onDelete="set null", nullable=true)
     */
    private $team;

    /**
     * @ORM\ManyToOne(targetEntity="SchoolBundle\Entity\School", inversedBy="student")
     * @ORM\JoinColumn(name="school_id", referencedColumnName="id", onDelete="set null", nullable=true)
     */
    private $school;

    /**
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\Role", inversedBy="student")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id", onDelete="set null", nullable=true)
     */
    private $role; //*

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->firstName.' '.$this->lastName;
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Student
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
     * @return Student
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
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * Set team
     *
     * @param \TeamBundle\Entity\Team $team
     *
     * @return Student
     */
    public function setTeam(\TeamBundle\Entity\Team $team = null)
    {
        $this->team = $team;

        return $this;
    }

    /**
     * Get team
     *
     * @return \TeamBundle\Entity\Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Set school
     *
     * @param \SchoolBundle\Entity\School $school
     *
     * @return Student
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
     * Set role
     *
     * @param \CoreBundle\Entity\Role $role
     *
     * @return Student
     */
    public function setRole(\CoreBundle\Entity\Role $role = null) //*
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \CoreBundle\Entity\Role
     */
    public function getRole() //*
    {
        return $this->role;
    }
}
