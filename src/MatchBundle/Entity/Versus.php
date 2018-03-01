<?php

namespace MatchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\MaxDepth;

/**
 * Versus
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="MatchBundle\Entity\VersusRepository")
 * @ExclusionPolicy("all")
 */
class Versus implements \JsonSerializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     * @MaxDepth(1)
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_match", type="datetimetz")
     * @Expose
     * @MaxDepth(1)
     */
    private $dateMatch;

    /**
     * @var string
     *
     * @ORM\Column(name="table_number", type="string", length=255)
     * @Expose
     * @MaxDepth(1)
     */
    private $tableNumber;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="finished", type="boolean")
     * @Expose
     * @MaxDepth(1)
     */
    private $finished = false;

    /**
     * @ORM\OneToMany(targetEntity="MatchBundle\Entity\Score", mappedBy="versus", cascade={"persist", "remove"})
     * @Expose
     * @MaxDepth(1)
     */
    private $score;

    /**
     * @var string
     *
     * @ORM\Column(name="step", type="integer", nullable=true)
     * @Expose
     * @MaxDepth(1)
     */
    private $step;

    /**
     * @var string
     *
     * @ORM\Column(name="final_group", type="integer", nullable=true)
     * @Expose
     * @MaxDepth(1)
     */
    private $finalGroup;

    /**
     * @ORM\ManyToOne(targetEntity="MatchBundle\Entity\Tournament", inversedBy="match", cascade={"persist"})
     * @Expose
     * @MaxDepth(1)
     */
    private $tournament;

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'date' => $this->getDateMatch(),
            'table' => $this->getTableNumber(),
            'score' => $this->getScore(),
        ];
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
     * Set dateMatch
     *
     * @param \DateTime $dateMatch
     *
     * @return Versus
     */
    public function setDateMatch($dateMatch)
    {
        $this->dateMatch = $dateMatch;

        return $this;
    }

    /**
     * Get dateMatch
     *
     * @return \DateTime
     */
    public function getDateMatch()
    {
        return $this->dateMatch;
    }

    /**
     * Set tableNumber
     *
     * @param string $tableNumber
     *
     * @return Versus
     */
    public function setTableNumber($tableNumber)
    {
        $this->tableNumber = $tableNumber;

        return $this;
    }

    /**
     * Get tableNumber
     *
     * @return string
     */
    public function getTableNumber()
    {
        return $this->tableNumber;
    }

    /**
     * Set step
     *
     * @param integer $step
     *
     * @return Versus
     */
    public function setStep($step)
    {
        $this->step = $step;

        return $this;
    }

    /**
     * Get step
     *
     * @return integer
     */
    public function getStep()
    {
        return $this->step;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->score = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add score
     *
     * @param \MatchBundle\Entity\Score $score
     *
     * @return Versus
     */
    public function addScore(\MatchBundle\Entity\Score $score)
    {
        $score->setVersus($this);
        $this->score[] = $score;

        return $this;
    }

    /**
     * Remove score
     *
     * @param \MatchBundle\Entity\Score $score
     */
    public function removeScore(\MatchBundle\Entity\Score $score)
    {
        $this->score->removeElement($score);
    }

    /**
     * Get score
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set finished
     *
     * @param boolean $finished
     *
     * @return Versus
     */
    public function setFinished($finished)
    {
        $this->finished = $finished;

        return $this;
    }

    /**
     * Get finished
     *
     * @return boolean
     */
    public function isFinished()
    {
        return $this->finished;
    }

    /**
     * Get finished
     *
     * @return boolean
     */
    public function getFinished()
    {
        return $this->finished;
    }

    /**
     * Set tournament
     *
     * @param \MatchBundle\Entity\Tournament $tournament
     *
     * @return Versus
     */
    public function setTournament(\MatchBundle\Entity\Tournament $tournament = null)
    {
        $this->tournament = $tournament;

        return $this;
    }

    /**
     * Get tournament
     *
     * @return \MatchBundle\Entity\Tournament
     */
    public function getTournament()
    {
        return $this->tournament;
    }

    /**
     * Set finalGroup
     *
     * @param integer $finalGroup
     *
     * @return Versus
     */
    public function setFinalGroup($finalGroup)
    {
        $this->finalGroup = $finalGroup;

        return $this;
    }

    /**
     * Get finalGroup
     *
     * @return integer
     */
    public function getFinalGroup()
    {
        return $this->finalGroup;
    }
}
