<?php
/**
 * LoadStudentData class file
 *
 * PHP Version 7.0
 *
 * @category Fixture
 * @package  CoreBundle\DataFixtures\ORM
 * @author   Guillaume <guillaume.torres91@gmail.com>
 * @license  All right reserved
 * @link     Null
 */
namespace CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SchoolBundle\Entity\Student;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TeamBundle\Entity\Team;
use UserBundle\Entity\User;

/**
 * Class LoadStudentData
 *
 * @category Fixture
 * @package  CoreBundle\DataFixtures\ORM
 * @author   Guillaume <guillaume.torres91@gmail.com>
 * @license  All right reserved
 * @link     Null
 */
class LoadStudentData extends AbstractFixture implements OrderedFixtureInterface
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
        $lorem = 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Ut enim ad minim veniam quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat';
        $nameList = explode(' ', $lorem);

        $teamCpt = 1;
        $roleCpt = 1;
        for ($i = 1; $i <= 24; $i++) {
            $student = new Student();
            $lastName = $nameList[array_rand($nameList)];
            $firstName = $nameList[array_rand($nameList)];
            $student->setFirstName($firstName);
            $student->setLastName($lastName);
            $student->setLevel(rand(1, 3));
            /** @var Team $team */
            $team = $this->getReference('team'.$teamCpt);
            $student->setTeam($team);
            $student->setSchool($team->getGroup()->getTeacher()->getSchool());
            $role = $this->getReference('role'.$roleCpt);
            $student->setRole($role);

            $manager->persist($student);
            $this->addReference('student'.$i, $student);

            if ($i%2 == 0) {
                $teamCpt++;
            }
            if ($i%2 == 0) {
                $roleCpt++;
            }
        }
        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 5;
    }
}
