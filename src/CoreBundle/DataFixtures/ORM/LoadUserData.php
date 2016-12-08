<?php
/**
 * LoadUserData class file
 *
 * PHP Version 7.0
 *
 * @category Fixture
 * @package  CoreBundle\DataFixtures\ORM
 * @author   Guillaume <guillaume.torres91@gmail.com>
 * @license  All right reserved
 * @link     Null
 */
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use UserBundle\Entity\User;

/**
 * Class LoadUserData
 *
 * @category Fixture
 * @package  CoreBundle\DataFixtures\ORM
 * @author   Guillaume <guillaume.torres91@gmail.com>
 * @license  All right reserved
 * @link     Null
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ObjectManager
     */
    protected $userManager;

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param ObjectManager $manager Object manager
     *
     * @return null
     */
    public function load(ObjectManager $manager)
    {
        $admin = $this->setAdmin();
        $this->addReference('admin', $admin);

        $teacher = $this->setTeacher();
        $this->addReference('teacher', $teacher);
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }

    /**
     * @param ContainerInterface|null $container Container
     *
     * @return null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->userManager = $container->get('fos_user.user_manager');
    }

    /**
     * Create Super admin user
     *
     * @return User
     */
    private function setAdmin()
    {
        /** @var User $user */
        $user = $this->userManager->createUser();
        $user->setFirstName('John');
        $user->setLastName('Doe');
        $user->setUsername('admin');
        $user->setEmail('admin@gmail.com');
        $user->setPlainPassword('admin');
        $user->setEnabled(true);
        $user->setLocked(false);
        $user->setRoles(array('ROLE_SUPER_ADMIN', 'ROLE_ADMIN'));
        $this->userManager->updateUser($user, true);

        return $user;
    }

    /**
     * Create admin user
     *
     * @return User
     */
    private function setTeacher()
    {
        /** @var User $user */
        $user = $this->userManager->createUser();
        $user->setFirstName('Paul');
        $user->setLastName('Watson');
        $user->setUsername('teacher');
        $user->setEmail('teacher@gmail.com');
        $user->setSchool($this->getReference('school0'));
        $user->setPlainPassword('password');
        $user->setEnabled(true);
        $user->setLocked(false);
        $user->setRoles(array('ROLE_ADMIN'));
        $this->userManager->updateUser($user, true);

        return $user;
    }
}
