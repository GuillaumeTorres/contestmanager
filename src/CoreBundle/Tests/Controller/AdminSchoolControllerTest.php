<?php
/**
 * AdminSchoolControllerTest class file
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
use SchoolBundle\Entity\School;

/**
 * AdminSchoolControllerTest class
 *
 * @category Test
 * @package  CoreBundle\Tests\Controller
 * @author   @author Guillaume <contact@guillaume-torres.fr>
 * @license  All right reserved
 * @link     Null
 */
class AdminSchoolControllerTest extends BaseTest
{
    /**
     * Test school list
     *
     * @return null
     */
    public function testIndex()
    {
        $crawler = $this->client->request('GET', $this->getRouter()->generate('admin_school_school_list'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Admin', $crawler->filter('title')->text());
    }

    /**
     * Test school creation
     *
     * @return null
     */
    public function testSchoolCreate()
    {
        $crawler = $this->client->request('GET', $this->getRouter()->generate('admin_school_school_create'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $action = $crawler->filter('div.sonata-ba-form form')->attr('action');
        $formId = explode('=', $action)[1];

        $form = $crawler->selectButton('btn_create_and_edit')->form();
        $form->setValues(
            array(
                $formId => array(
                  'name' => 'lorem ipsum',
                ),
            )
        );
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('L\'élément "lorem ipsum" a été créé avec succès.', $crawler->filter('div.alert-success')->text());
    }

    /**
     * Test school edition
     *
     * @return null
     */
    public function testSchoolEdit()
    {
        /** @var School $school */
        $school = $this->fixtures->getReference('school0');

        $crawler = $this->client->request('GET', $this->getRouter()->generate(
            'admin_school_school_edit',
            ['id' => $school->getId()]
        ));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $action = $crawler->filter('div.sonata-ba-form form')->attr('action');
        $formId = explode('=', $action)[1];

        $form = $crawler->selectButton('btn_update_and_list')->form();
        $form->setValues(array($formId => array('name' => 'new school')));
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('L\'élément "new school" a été mis à jour avec succès.', $crawler->filter('div.alert-success')->text());
    }

    /**
     * Test school deletion
     *
     * @return null
     */
    public function testSchoolDelete()
    {
        /** @var School $school */
        $school = $this->fixtures->getReference('school0');

        $crawler = $this->client->request('GET', $this->getRouter()->generate(
            'admin_school_school_delete',
            ['id' => $school->getId()]
        ));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Êtes-vous sûr de vouloir supprimer l\'élément "'.$school->getName().'" sélectionné?', $crawler->filter('div.box-body')->text());

        $form = $crawler->filter('button:contains("supprimer")')->eq(0)->form();
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('L\'élément "'.$school->getName().'" a été supprimé avec succès.', $crawler->filter('div.alert-success')->text());
    }
}
