<?php
/**
 * AdminStudentControllerTest class file
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
use SchoolBundle\Entity\Student;
use TeamBundle\Entity\Team;

/**
 * AdminStudentControllerTest class
 *
 * @category Test
 * @package  CoreBundle\Tests\Controller
 * @author   @author Guillaume <contact@guillaume-torres.fr>
 * @license  All right reserved
 * @link     Null
 */
class AdminStudentControllerTest extends BaseTest
{
    /**
     * Test student list
     *
     * @return null
     */
    public function testIndex()
    {
        $crawler = $this->client->request('GET', $this->getRouter()->generate('admin_school_student_list'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Admin', $crawler->filter('title')->text());
    }

    /**
     * Test student creation
     *
     * @return null
     */
    public function testStudentCreate()
    {
        $crawler = $this->client->request('GET', $this->getRouter()->generate('admin_school_student_create'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $action = $crawler->filter('div.sonata-ba-form form')->attr('action');
        $formId = explode('=', $action)[1];

        $form = $crawler->selectButton('btn_create_and_edit')->form();
        /** @var Team $team */
        $team = $this->fixtures->getReference('team1');
        $form->setValues(
            array(
                $formId => array(
                  'firstName' => 'John',
                  'lastName' => 'Smith',
                  'level' => 3,
                  'team' => $team->getId(),
                ),
            )
        );
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('a été créé avec succès.', $crawler->filter('div.alert-success')->text());
    }

    /**
     * Test student edition
     *
     * @return null
     */
    public function testStudentEdit()
    {
        /** @var Student $student */
        $student = $this->fixtures->getReference('student1');

        $crawler = $this->client->request('GET', $this->getRouter()->generate(
            'admin_school_student_edit',
            ['id' => $student->getId()]
        ));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $action = $crawler->filter('div.sonata-ba-form form')->attr('action');
        $formId = explode('=', $action)[1];

        $form = $crawler->selectButton('btn_update_and_list')->form();
        $form->setValues(array($formId => array('firstName' => 'Toto', 'lastName' => 'Doe')));
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('L\'élément "Toto Doe" a été mis à jour avec succès.', $crawler->filter('div.alert-success')->text());
    }

    /**
     * Test student deletion
     *
     * @return null
     */
    public function testStudentDelete()
    {
        /** @var Student $student */
        $student = $this->fixtures->getReference('student1');

        $crawler = $this->client->request('GET', $this->getRouter()->generate(
            'admin_school_school_delete',
            ['id' => $student->getId()]
        ));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Êtes-vous sûr de vouloir supprimer l\'élément', $crawler->filter('div.box-body')->text());

        $form = $crawler->filter('button:contains("supprimer")')->eq(0)->form();
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('a été supprimé avec succès.', $crawler->filter('div.alert-success')->text());
    }
}
