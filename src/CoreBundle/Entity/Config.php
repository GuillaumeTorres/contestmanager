<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Config
 *
 * @ORM\Table()
 * @ORM\Entity
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
     * @SuppressWarnings(PHPMD)
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
     * @var integer
     *
     * @ORM\Column(name="time_interval", type="integer", nullable=true)
     */
    //private $time_interval;


    /**
     * @var integer
     *
     * @ORM\Column(name="start_time", type="integer", nullable=true)
     */
    //private $start_time;

    /**
     * @return int
     */
    /*public function getStartTime()
    {
        return $this->start_time;
    }*/

    /**
     * @param int $start_time
     */
    /*public function setStartTime($start_time)
    {
        $this->start_time = $start_time;
    }*/

    /**
     * @return int
     */
    /*public function getTimeInterval()
    {
        return $this->time_interval;
    }*/

    /**
     * @param int $time_interval
     */
    /*public function setTimeInterval($time_interval)
    {
        $this->time_interval = $time_interval;
    }*/

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
