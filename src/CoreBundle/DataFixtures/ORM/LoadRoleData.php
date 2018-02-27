<?php
/**
 * LoadRoleData class file
 *
 * PHP Version 7.0
 *
 * @category Fixture
 * @package  CoreBundle\DataFixtures\ORM
 * @author   Louis <louis.richer@ynov.com>
 * @license  All right reserved
 * @link     Null
 */
namespace CoreBundle\DataFixtures\ORM;

use CoreBundle\Entity\Role;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadRoleData
 *
 * @category Fixture
 * @package  CoreBundle\DataFixtures\ORM
 * @author   Louis <louis.richer@ynov.com>
 * @license  All right reserved
 * @link     Null
 */
class LoadRoleData extends AbstractFixture implements OrderedFixtureInterface
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
        $lorem = 'Lorem ipsum dolor amet consectetur adipiscing elit sed eiusmod tempor incididunt labore dolore magna aliqua enim ad minim veniam quis nostrud exercitation ullamco laboris nisi aliquip ex ea commodo consequat';
        $nameList = explode(' ', $lorem);

        for ($i = 1; $i <= 12; $i++) {
            $role = new Role();
            $roleName = $nameList[array_rand($nameList)];
            $role->setName($roleName);

            $manager->persist($role);
            $this->addReference('role'.$i, $role);

        }
        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 4;
    }
}