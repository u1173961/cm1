<?php

namespace CM\InterfaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken,
    Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use CM\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class GameController extends Controller
{    
	public function showBoardAction($gameID = null) {
		if (is_null($gameID)) {  
			$gameID = 'x';
		} else {
			//get white/black specific board
		}
    	$pieces = $this->getHTMLPieces();
        return $this->render('CMInterfaceBundle:Game:board.html.twig', 
        		array('gameID' => $gameID, 'pieces' => $pieces));	
	}
	
    public function newGameAction(Request $request)
    {
//     	echo '<pre>';
//     	var_dump($request->request->get('opponent'));
//     	echo '</pre>';
    	$user = $this->getUser();
    	//get game variables
    	$opponent = $request->request->get('opponent');
    	$skill = $request->request->get('skill');
    	$duration = $request->request->get('duration') * 60; //TODO: mins. or secs.?
    	
    	//create/join game
    	if ($opponent == 1) {
    		//human
	    	$em = $this->getDoctrine()->getManager();
	    	//TODO: skill
	    	$game = $em->getRepository('CMInterfaceBundle:Game')->findOneBy(array('inProgress' => false, 'length' => $duration));
	    	if ($game) {
	    		$playing = $game->getPlayers()->indexOf($user);
	    		if ($playing === false) {
		    		echo 'found game';
		    		$game->setBlackPlayer($user);
		    		//$colour = 'b';
		    		$game->setInProgress(true);
	    			$em->flush();
	    		}
	    	} else {
	    		//create new game
    			$game = $this->get('game_factory')->createNewGame($duration, $user);
	    		$em->persist($game);
    			//wait for match - 10 seconds?
	    		$em->flush();
	    	}
    	} else {
    		//computer
    	}
    	
    	return $this->redirect($this->generateUrl('cm_interface_play', array('gameID' => $game->getId())));
    }
	
    public function playAction($gameID)
    {    	
    	$user = $this->getUser();
    	if ($user) {
    		//$name = $user->getUsername();
    	}
    	
    	$em = $this->getDoctrine()->getManager();
	    //TODO: skill
	    $game = $em->getRepository('CMInterfaceBundle:Game')->find($gameID);
	    if ($game) {
	    	//make sure valid user for game
	    	$user = $this->getUser();
	    	if (!$game->getPlayers()->contains($user)) {
	    		//throw error
	    	} else {
	    		//valid user
		    	if ($game->getPlayers()->indexOf($user) == 0) {
		    		$colour = 'w';
		    	} else {
		    		$colour = 'b';
		    	}
	    	}
	    } else {
	    	//throw error
	    }
	    	
	    $pieces = $this->getHTMLPieces($colour);
	    	
	    return $this->render('CMInterfaceBundle:Game:index.html.twig', array('pieces' => $pieces, 'game' => $game));
    }
	
    public function resignAction()
    {
    	return $this->render('CMInterfaceBundle:Game:index.html.twig', array());    	
    }
	
    public function offerDrawAction()
    {
    	return $this->render('CMInterfaceBundle:Game:index.html.twig', array());    	
    }
	
    public function toggleChatAction()
    {
    	return $this->render('CMInterfaceBundle:Game:index.html.twig', array());    	
    }
	
    public function startAction()
    {
    	$pieces = $this->getHTMLPieces();
    	
        return $this->render('CMInterfaceBundle:Game:index.html.twig', array('pieces' => $pieces));
    }
    
    private function getHTMLPieces($for = 'w') {
    	//TODO: move to helper/service & switch white/black depending on player
    	if ($for = 'w') {
	    	$pieces = array(
	    			array('id' => 'b_rook_1', 'img' => '&#9820;'),
	    			array('id' => 'b_knight_1', 'img' => '&#9822;'),
	    			array('id' => 'b_bishop_1', 'img' => '&#9821;'),
	    			array('id' => 'b_queen', 'img' => '&#9819;'),
	    			array('id' => 'b_king', 'img' => '&#9818;'),
	    			array('id' => 'b_bishop_2', 'img' => '&#9821;'),
	    			array('id' => 'b_knight_2', 'img' => '&#9822;'),
	    			array('id' => 'b_rook_2', 'img' => '&#9820;'),
	    			array('id' => 'b_pawn_1', 'img' => '&#9823;'),
	    			array('id' => 'b_pawn_2', 'img' => '&#9823;'),
	    			array('id' => 'b_pawn_3', 'img' => '&#9823;'),
	    			array('id' => 'b_pawn_4', 'img' => '&#9823;'),
	    			array('id' => 'b_pawn_5', 'img' => '&#9823;'),
	    			array('id' => 'b_pawn_6', 'img' => '&#9823;'),
	    			array('id' => 'b_pawn_7', 'img' => '&#9823;'),
	    			array('id' => 'b_pawn_8', 'img' => '&#9823;'),
	    			array('id' => 'w_pawn_1', 'img' => '&#9817;'),
	    			array('id' => 'w_pawn_2', 'img' => '&#9817;'),
	    			array('id' => 'w_pawn_3', 'img' => '&#9817;'),
	    			array('id' => 'w_pawn_4', 'img' => '&#9817;'),
	    			array('id' => 'w_pawn_5', 'img' => '&#9817;'),
	    			array('id' => 'w_pawn_6', 'img' => '&#9817;'),
	    			array('id' => 'w_pawn_7', 'img' => '&#9817;'),
	    			array('id' => 'w_pawn_8', 'img' => '&#9817;'),
	    			array('id' => 'w_rook_1', 'img' => '&#9814;'),
	    			array('id' => 'w_knight_1', 'img' => '&#9816;'),
	    			array('id' => 'w_bishop_1', 'img' => '&#9815;'),
	    			array('id' => 'w_queen', 'img' => '&#9813;'),
	    			array('id' => 'w_king', 'img' => '&#9812;'),
	    			array('id' => 'w_bishop_2', 'img' => '&#9815;'),
	    			array('id' => 'w_knight_2', 'img' => '&#9816;'),
	    			array('id' => 'w_rook_2', 'img' => '&#9814;')
	    	);
    	} else {
	    	$pieces = array(
	    			array('id' => 'w_rook_1', 'img' => '&#9814;'),
	    			array('id' => 'w_knight_1', 'img' => '&#9816;'),
	    			array('id' => 'w_bishop_1', 'img' => '&#9815;'),
	    			array('id' => 'w_king', 'img' => '&#9812;'),
	    			array('id' => 'w_queen', 'img' => '&#9813;'),
	    			array('id' => 'w_bishop_2', 'img' => '&#9815;'),
	    			array('id' => 'w_knight_2', 'img' => '&#9816;'),
	    			array('id' => 'w_rook_2', 'img' => '&#9814;'),
	    			array('id' => 'w_pawn_1', 'img' => '&#9817;'),
	    			array('id' => 'w_pawn_2', 'img' => '&#9817;'),
	    			array('id' => 'w_pawn_3', 'img' => '&#9817;'),
	    			array('id' => 'w_pawn_4', 'img' => '&#9817;'),
	    			array('id' => 'w_pawn_5', 'img' => '&#9817;'),
	    			array('id' => 'w_pawn_6', 'img' => '&#9817;'),
	    			array('id' => 'w_pawn_7', 'img' => '&#9817;'),
	    			array('id' => 'w_pawn_8', 'img' => '&#9817;'),
	    			array('id' => 'b_pawn_1', 'img' => '&#9823;'),
	    			array('id' => 'b_pawn_2', 'img' => '&#9823;'),
	    			array('id' => 'b_pawn_3', 'img' => '&#9823;'),
	    			array('id' => 'b_pawn_4', 'img' => '&#9823;'),
	    			array('id' => 'b_pawn_5', 'img' => '&#9823;'),
	    			array('id' => 'b_pawn_6', 'img' => '&#9823;'),
	    			array('id' => 'b_pawn_7', 'img' => '&#9823;'),
	    			array('id' => 'b_pawn_8', 'img' => '&#9823;'),
	    			array('id' => 'b_rook_1', 'img' => '&#9820;'),
	    			array('id' => 'b_knight_1', 'img' => '&#9822;'),
	    			array('id' => 'b_bishop_1', 'img' => '&#9821;'),
	    			array('id' => 'b_king', 'img' => '&#9818;'),
	    			array('id' => 'b_queen', 'img' => '&#9819;'),
	    			array('id' => 'b_bishop_2', 'img' => '&#9821;'),
	    			array('id' => 'b_knight_2', 'img' => '&#9822;'),
	    			array('id' => 'b_rook_2', 'img' => '&#9820;')
	    	);    		
    	}
    	
    	return $pieces;
    }
     
    public function guestPlayAction()
    {    	
     	$userManager = $this->get('fos_user.user_manager');	
     	//get inactive guest account
		$em = $this->getDoctrine()->getManager();
		$user = $em->getRepository('CMUserBundle:User')->findInactiveGuest();
		if (!$user) {
			$name = "Guest 00" . rand(1,1000);
			$user = $userManager->createUser();
			$user->setUsername($name);		
			$user->setEmail($name);
			$user->setPassword("");
			$user->setRegistered(false);
			$user->setLastActiveTime(new \DateTime());
		} else {
			$user = $user[0];
			$name = $user->getUsername();
			$user->setLastActiveTime(new \DateTime());
		}
		$userManager->updateUser($user);
		//set login token
		$token = new UsernamePasswordToken($user, $user->getPassword(), "main", $user->getRoles());
		$this->get("security.context")->setToken($token);		
		// fire login
		$event = new InteractiveLoginEvent($this->get("request"), $token);
		$this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
    	
        return $this->render('CMInterfaceBundle:Game:index.html.twig', array('name' => $name));
    }
}
