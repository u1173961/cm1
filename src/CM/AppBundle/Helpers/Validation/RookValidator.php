<?php

namespace CM\AppBundle\Helpers\Validation;

use CM\AppBundle\Entity\Game;
use CM\UserBundle\Entity\User;
use CM\AppBundle\Entity\Board;

/**
 * Rook validator
 */
class RookValidator extends ValidationHelper
{		
	/**
	 * Validate rook movement
	 * @param array  $move
	 */
	public function validatePiece($move) {
    	$from = $move['from'];
    	$to = $move['to'];
    	if (($from[0] == $to[0] && !$this->xAxisBlocked($from[1], $to[1], $from[0]))
    		|| ($from[1] == $to[1] && !$this->yAxisBlocked($from[0], $to[0], $from[1]))) {
    		return true;
    	}
    	
    	return false;
	}
}
