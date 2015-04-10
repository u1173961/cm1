<?php

namespace CM\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="board")
 */
class Board
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="array")
     */
    protected $board;

    /**
     * @ORM\Column(type="array")
     */
    protected $unmoved;
    
    /**
     * Has pawn been swapped
     * 
     * @ORM\Column(type="boolean")
     */
    private $pawnSwapped;

    /**
     * The position of a pawn vulnerable to En passant
     * @ORM\Column(type="array", nullable=true)
     */
    protected $enPassantAvailable;

    /**
     * @ORM\Column(type="array")
     */
    protected $takenPieces;

    public function __construct()
    {
        $this->setDefaults();
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
     * Set default board & unmoved pieces
     *
     * @return array 
     */
    public function setDefaults()
    {
    	$this->setDefaultBoard();
    	$this->setDefaultUnmoved();
    	$this->setDefaultTaken();
    	$this->enPassantAvailable = null;
    	$this->pawnSwapped = false;
    }

    /**
     * set default board
     *
     * @return array 
     */
    private function setDefaultBoard()
    {
        $this->board = array(
    		array('w_r','w_n','w_b','w_q','w_k','w_b','w_n','w_r'),
    		array('w_p','w_p','w_p','w_p','w_p','w_p','w_p','w_p'),
    		array(false, false, false, false, false, false, false, false),
    		array(false, false, false, false, false, false, false, false),
    		array(false, false, false, false, false, false, false, false),
    		array(false, false, false, false, false, false, false, false),
    		array('b_p','b_p','b_p','b_p','b_p','b_p','b_p','b_p'),
    		array('b_r','b_n','b_b','b_q','b_k','b_b','b_n','b_r')
    	);
    }
    
    private function setDefaultTaken() {
    	$this->takenPieces = array(
    			'w_p' => 0, 'w_r' => 0, 'w_n' => 0, 'w_b' => 0, 'w_q' => 0,
    			'b_p' => 0, 'b_r' => 0, 'b_n' => 0, 'b_b' => 0, 'b_q' => 0
    	);    	
    }

    /**
     * Set board
     *
     * @param array $board
     * @return Board
     */
    public function setBoard(array $board)
    {
        $this->board = $board;

        return $this;
    }

    /**
     * Get board
     *
     * @return array 
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * Set unmoved
     *
     * @param array $unmoved
     * @return Board
     */
    public function setUnmoved(array $unmoved)
    {
        $this->unmoved = $unmoved;

        return $this;
    }

    /**
     * set default unmoved
     *
     * @return array 
     */
    public function setDefaultUnmoved()
    {
        $this->unmoved = array(
    			array(true, true, true, true, true, true, true, true),
    			array(true, true, true, true, true, true, true, true),
    			array(false, false, false, false, false, false, false, false),
    			array(false, false, false, false, false, false, false, false),
    			array(false, false, false, false, false, false, false, false),
    			array(false, false, false, false, false, false, false, false),
    			array(true, true, true, true, true, true, true, true),
    			array(true, true, true, true, true, true, true, true)
    	);
    }

    /**
     * Get unmoved
     *
     * @return array 
     */
    public function getUnmoved()
    {
        return $this->unmoved;
    }

    /**
     * Set taken pieces
     *
     * @param array $taken
     * @return Board
     */
    public function setTaken(array $taken)
    {
        $this->takenPieces = $taken;

        return $this;
    }

    /**
     * Get taken pieces
     *
     * @return array 
     */
    public function getTaken()
    {
        return $this->takenPieces;
    }

    /**
     * Add taken piece
     *
     * @param string $taken
     * @return Board
     */
    public function addTaken($taken)
    {
        $this->takenPieces[$taken]++;

        return $this;
    }
    
    /**
     * Mark piece as moved
     * @param int $row
     * @param int $column
     */
    public function setPieceAsMoved($row, $column) {
    	$this->unmoved[$row][$column] = false;
    }
    
    /**
     * Check if piece is moved
     * @param int $row
     * @param int $column
     */
    public function getPieceIsMoved($row, $column) {
    	return !$this->unmoved[$row][$column];
    }

    /**
     * Update board
     *
     * @param array $from	[y,x]
     * @param array $to		[y,x]
     * @return Board
     */
    public function updateBoard(array $from, array $to)
    {
    	if ($this->board[$to[0]][$to[1]]) {
    		//keep track of taken pieces for reloads
    		$this->taken[] = $this->board[$to[0]][$to[1]];
    	}
    	//move piece
        $this->board[$to[0]][$to[1]] = $this->board[$from[0]][$from[1]];
        $this->board[$from[0]][$from[1]] = false;

        return $this;
    }

    /**
     * Set Piece
     *
     * @param array $square [y,x]
     * @param string $piece
     * @return Board
     */
    public function setPiece(array $square, $piece)
    {
        $this->board[$square[0]][$square[1]] = $piece;

        return $this;
    }

    /**
     * Flag piece as swapped
     *
     * @param boolean $pawnSwapped
     * @return Game
     */
    public function setPawnSwapped($swapped)
    {
        $this->pawnSwapped = $swapped;

        return $this;
    }

    /**
     * Check if piece has swapped
     *
     * @return boolean 
     */
    public function getPawnSwapped()
    {
        return $this->pawnSwapped;
    }

    /**
     * Set indices for a piece vulnerable to En passant
     *
     * @param array|null $pawnPosition The vulnerable pawn's position
     * @return Board
     */
    public function setEnPassantAvailable($pawnPosition)
    {
        $this->enPassantAvailable = $pawnPosition;

        return $this;
    }

    /**
     * Get indices for a piece vulnerable to En passant
     *
     * @return null if En passant is unavailable
     */
    public function getEnPassantAvailable()
    {
        return $this->enPassantAvailable;
    }
}
