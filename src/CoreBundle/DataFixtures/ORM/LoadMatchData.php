<?php
/**
 * LoadMatchData class file
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
use MatchBundle\Entity\Versus;
use SchoolBundle\Entity\Student;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use TeamBundle\Entity\Team;
use UserBundle\Entity\User;

/**
 * Class LoadMatchData
 *
 * @category Fixture
 * @package  CoreBundle\DataFixtures\ORM
 * @author   Guillaume <guillaume.torres91@gmail.com>
 * @license  All right reserved
 * @link     Null
 */
class LoadMatchData extends AbstractFixture implements OrderedFixtureInterface
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
        for ($i = 0; $i < 3; $i++) {
            $match = new Versus();
            $match->setTournament($this->getReference('tournament'));
            $match->setDateMatch(new \DateTime());
            $match->setTableNumber(23);

            $manager->persist($match);
            $this->addReference('match'.$i, $match);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 7;
    }
}
