<?php
// src/CM/InterfaceBundle/Entity/Game.php

namespace CM\InterfaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\Common\Collections\ArrayCollection;
//use CM\UserBundle\Entity\User as User;

/**
 * @ORM\Entity
 * @ORM\Table(name="game")
 * @ORM\Entity(repositoryClass="CM\InterfaceBundle\Repository\GameRepository")
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Board", cascade={"persist"})
     */
    private $board;
    
    /**
     * Players
     *
     * @ORM\ManyToMany(targetEntity="CM\UserBundle\Entity\User", inversedBy="currentGames", cascade={"persist"})
     * @ORM\JoinTable(name="game_players",
     * joinColumns={@ORM\JoinColumn(name="game_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")})
     */
    private $players;

    /**
     * @ORM\OneToOne(targetEntity="CM\UserBundle\Entity\User", cascade={"persist"})
     */
    private $activePlayer;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $length;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $inProgress;
    
    private $whiteTimeLeft;
    private $blackTimeLeft;
    
    /**
     * Constructor
     */
    public function __construct($board)
    {
        $this->players = new \Doctrine\Common\Collections\ArrayCollection();
        $this->board = $board;
        $this->inProgress = false;
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
     * Set length
     *
     * @param integer $length
     * @return Game
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Get length
     *
     * @return integer 
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set game in progress
     *
     * @param boolean $inProgress
     * @return Game
     */
    public function setInProgress($inProgress)
    {
        $this->inProgress = $inProgress;

        return $this;
    }

    /**
     * Check if game is in progress
     *
     * @return boolean 
     */
    public function getInProgress()
    {
        return $this->inProgress;
    }

    /**
     * Set board
     *
     * @param \CM\InterfaceBundle\Entity\Board $board
     * @return Game
     */
    public function setBoard(\CM\InterfaceBundle\Entity\Board $board = null)
    {
        $this->board = $board;

        return $this;
    }

    /**
     * Get board
     *
     * @return \CM\InterfaceBundle\Entity\Board 
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * Set whitePlayer
     *
     * @param \CM\UserBundle\Entity\User $whitePlayer
     * @return Game
     */
    public function setWhitePlayer(\CM\UserBundle\Entity\User $whitePlayer = null)
    {
        $this->players->set(0, $whitePlayer);

        return $this;
    }

    /**
     * Get whitePlayer
     *
     * @return \CM\UserBundle\Entity\User 
     */
    public function getWhitePlayer()
    {
        return $this->players->get(0);
    }

    /**
     * Set blackPlayer
     *
     * @param \CM\UserBundle\Entity\User $blackPlayer
     * @return Game
     */
    public function setBlackPlayer(\CM\UserBundle\Entity\User $blackPlayer)
    {
       $this->players->set(1, $blackPlayer);

        return $this;
    }

    /**
     * Get blackPlayer
     *
     * @return \CM\UserBundle\Entity\User 
     */
    public function getBlackPlayer()
    {
        return $this->players->get(1);
    }

    /**
     * Set active player
     *
     * @param \CM\UserBundle\Entity\User $player
     * @return Game
     */
    public function setActivePlayer(\CM\UserBundle\Entity\User $player)
    {
        $this->activePlayer = $player;

        return $this;
    }

    /**
     * Get active player
     *
     * @return \CM\UserBundle\Entity\User 
     */
    public function getActivePlayer()
    {
        return $this->activePlayer;
    }

    /**
     * Add players
     *
     * @param \CM\UserBundle\Entity\User $players
     * @return Game
     */
    public function addPlayer(\CM\UserBundle\Entity\User $player)
    {
        $this->players[] = $player;

        return $this;
    }

    /**
     * Remove players
     *
     * @param \CM\UserBundle\Entity\User $players
     */
    public function removePlayer(\CM\UserBundle\Entity\User $player)
    {
        $this->players->removeElement($player);
    }

    /**
     * Get players
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlayers()
    {
        return $this->players;
    }
}
