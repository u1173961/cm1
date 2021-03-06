<?php

namespace CM\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CM\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;

class LoadData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface, ORMFixtureInterface
{
	/**
	 * @var ContainerInterface
	 */
	private $container;
	
	/**
	 * {@inheritDoc}
	 */
	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $manager)
	{
		//create users
		$userManager = $this->container->get('fos_user.user_manager');
		
		$user1 = $userManager->createUser();
		$user1->setUsername('Rex');
		$user1->setPlainPassword('pass');
		$user1->setRegistered(true);	
		$user1->setEmail('me@here.com');
		$user1->setLastActiveTime(new \DateTime());
		$user1->setEnabled(true);
        $user1->setRoles(array('ROLE_ADMIN'));
        $userManager->updateUser($user1, true);
		
		$user2 = $userManager->createUser();
		$user2->setUsername('Rex2');
		$user2->setPlainPassword('pass');
		$user2->setRegistered(true);	
		$user2->setEmail('me@here2.com');
		$user2->setLastActiveTime(new \DateTime());
		$user2->setEnabled(true);
		$user2->setChatty(false);
        $userManager->updateUser($user2, true);
	}

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
