<?php
/**
 * BaseTest class file
 *
 * PHP Version 7
 *
 * @category Test
 * @package  CoreBundle\Tests\Controller
 * @author   @author Guillaume <contact@guillaume-torres.fr>
 * @license  All right reserved
 * @link     Null
 */
namespace CoreBundle\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * BaseTest class
 *
 * @category Test
 * @package  CoreBundle\Tests\Controller
 * @author   @author Guillaume <contact@guillaume-torres.fr>
 * @license  All right reserved
 * @link     Null
 */
class BaseTest extends WebTestCase
{
    protected $fixtures;
    protected $client;

    /**
     * Test user list
     *
     * @return null
     */
    public function testIndex()
    {
        $crawler = $this->client->request('GET', $this->getRouter()->generate('sonata_admin_dashboard'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Admin', $crawler->filter('title')->text());
    }

    /**
     * @inheritDoc
     *
     * @return null
     */
    protected function setUp()
    {
        $fixtures = array(
          'CoreBundle\DataFixtures\ORM\LoadSchoolData',
          'CoreBundle\DataFixtures\ORM\LoadUserData',
          'CoreBundle\DataFixtures\ORM\LoadGroupData',
          'CoreBundle\DataFixtures\ORM\LoadTeamData',
          'CoreBundle\DataFixtures\ORM\LoadRoleData',
          'CoreBundle\DataFixtures\ORM\LoadStudentData',
          'CoreBundle\DataFixtures\ORM\LoadRoleData',
          'CoreBundle\DataFixtures\ORM\LoadTournamentData',
          'CoreBundle\DataFixtures\ORM\LoadConfigData',
          'CoreBundle\DataFixtures\ORM\LoadMatchData',
        );

        $this->fixtures = $this->loadFixtures($fixtures)->getReferenceRepository();
        $this->client = $this->makeClient(true);
    }

    /**
     * @return object
     */
    protected function getRouter()
    {
        return $this->getContainer()->get('router');
    }
}
