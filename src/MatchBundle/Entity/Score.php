<?php

namespace MatchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Score
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="MatchBundle\Entity\ScoreRepository")
 */
class Score
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="score", type="integer", nullable=true)
     */
    private $score;
    
    /**
     * @ORM\ManyToOne(targetEntity="TeamBundle\Entity\Team", inversedBy="score")
    */
    private $team;
    
    /**
     * @ORM\ManyToOne(targetEntity="MatchBundle\Entity\Versus", inversedBy="score")
    */
    private $versus;

    /**
     * @return string
     */
    public function __toString()
    {
        return 'team';
//        return $this->team;
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
     * Set score
     *
     * @param integer $score
     *
     * @return Score
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set team
     *
     * @param \TeamBundle\Entity\Team $team
     *
     * @return Score
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
     * Set versus
     *
     * @param \MatchBundle\Entity\Versus $versus
     *
     * @return Score
     */
    public function setVersus(\MatchBundle\Entity\Versus $versus = null)
    {
        $this->versus = $versus;

        return $this;
    }

    /**
     * Get versus
     *
     * @return \MatchBundle\Entity\Versus
     */
    public function getVersus()
    {
        return $this->versus;
    }
}
