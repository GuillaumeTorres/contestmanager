<?php
/**
 * LoadConfigData class file
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

use CoreBundle\Entity\Config;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadConfigData
 *
 * @category Fixture
 * @package  CoreBundle\DataFixtures\ORM
 * @author   Guillaume <guillaume.torres91@gmail.com>
 * @license  All right reserved
 * @link     Null
 */
class LoadConfigData extends AbstractFixture
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
        $config = new Config();
        $config->setName('Configuration');
        $config->setRoomNumber(2);
        $config->setLevelMax(9);
        //$config->setTimeInterval(10);
        //$config->setStartTime(9);

        $manager->persist($config);
        $this->addReference('config', $config);
        $manager->flush();
    }
}
