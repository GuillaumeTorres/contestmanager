<?php
/**
 * AdminGroupControllerTest class file
 *
 * PHP Version 7
 *
 * @category Test
 * @package  CoreBundle\Tests\Controller
 * @author   @author Guillaume <contact@guillaume-torres.fr>
 * @license  All right reserved
 * @link     Null
 */
namespace CoreBundle\Tests\Controller;

use CoreBundle\Tests\BaseTest;
use MatchBundle\Entity\GroupMatch;
use UserBundle\Entity\User;

/**
 * AdminGroupControllerTest class
 *
 * @category Test
 * @package  CoreBundle\Tests\Controller
 * @author   @author Guillaume <contact@guillaume-torres.fr>
 * @license  All right reserved
 * @link     Null
 */
class AdminGroupControllerTest extends BaseTest
{
    /**
     * Test group list
     *
     * @return null
     */
    public function testIndex()
    {
        $crawler = $this->client->request('GET', $this->getRouter()->generate('admin_match_groupmatch_list'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Admin', $crawler->filter('title')->text());
    }

    /**
     * Test group creation
     *
     * @return null
     */
    public function testGroupCreate()
    {
        $crawler = $this->client->request('GET', $this->getRouter()->generate('admin_match_groupmatch_create'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $action = $crawler->filter('div.sonata-ba-form form')->attr('action');
        $formId = explode('=', $action)[1];
        /** @var User $teacher */
        $teacher = $this->fixtures->getReference('teacher0');

        $form = $crawler->selectButton('btn_create_and_edit')->form();
        $form->setValues(
            array(
                $formId => array(
                  'name' => 'lorem ipsum',
                  'level' => 3,
                  'teacher' => $teacher->getId(),
                ),
            )
        );
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('L\'élément "lorem ipsum" a été créé avec succès.', $crawler->filter('div.alert-success')->text());
    }

    /**
     * Test group edition
     *
     * @return null
     */
    public function testGroupEdit()
    {
        /** @var GroupMatch $group */
        $group = $this->fixtures->getReference('group1');

        $crawler = $this->client->request('GET', $this->getRouter()->generate(
            'admin_match_groupmatch_edit',
            ['id' => $group->getId()]
        ));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $action = $crawler->filter('div.sonata-ba-form form')->attr('action');
        $formId = explode('=', $action)[1];

        $form = $crawler->selectButton('btn_update_and_list')->form();
        $form->setValues(array($formId => array('name' => 'new group')));
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('L\'élément "new group" a été mis à jour avec succès.', $crawler->filter('div.alert-success')->text());
    }

    /**
     * Test group deletion
     *
     * @return null
     */
    public function testGroupDelete()
    {
        /** @var GroupMatch $group */
        $group = $this->fixtures->getReference('group1');

        $crawler = $this->client->request('GET', $this->getRouter()->generate(
            'admin_match_groupmatch_delete',
            ['id' => $group->getId()]
        ));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Êtes-vous sûr de vouloir supprimer l\'élément "'.$group->getName().'" sélectionné?', $crawler->filter('div.box-body')->text());

        $form = $crawler->filter('button:contains("supprimer")')->eq(0)->form();
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('L\'élément "'.$group->getName().'" a été supprimé avec succès.', $crawler->filter('div.alert-success')->text());
    }
}
