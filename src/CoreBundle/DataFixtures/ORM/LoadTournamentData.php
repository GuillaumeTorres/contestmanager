<?php
/**
 * LoadTournamentData class file
 *
 * PHP Version 7.0
 *
 * @category Fixture
 * @package  CoreBundle\DataFixtures\ORM
 * @author   Guillaume <contact@guillaume-torres.fr>
 * @license  All right reserved
 * @link     Null
 */
namespace CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MatchBundle\Entity\Tournament;
use SchoolBundle\Entity\Student;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use TeamBundle\Entity\Team;
use UserBundle\Entity\User;

/**
 * Class LoadTournamentData
 *
 * @category Fixture
 * @package  CoreBundle\DataFixtures\ORM
 * @author   Guillaume <guillaume.torres91@gmail.com>
 * @license  All right reserved
 * @link     Null
 */
class LoadTournamentData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param ObjectManager $manager Object manager
     *
     * @return null
     */
    public function load(ObjectManager $manager)
    {
        $tournament = new Tournament();
        $tournament->setName('Tournament');
        $tournament->setDate(new \DateTime());
        $tournament->setHalfDay('Am');

        $manager->persist($tournament);
        $manager->flush();
        $this->addReference('tournament', $tournament);
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 6;
    }
}
