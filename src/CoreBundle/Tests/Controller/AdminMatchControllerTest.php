<?php
/**
 * AdminMatchControllerTest class file
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
use MatchBundle\Entity\Versus;
use UserBundle\Entity\User;

/**
 * AdminMatchControllerTest class
 *
 * @category Test
 * @package  CoreBundle\Tests\Controller
 * @author   @author Guillaume <contact@guillaume-torres.fr>
 * @license  All right reserved
 * @link     Null
 */
class AdminMatchControllerTest extends BaseTest
{
    /**
     * Test match list
     *
     * @return null
     */
    public function testIndex()
    {
        $crawler = $this->client->request('GET', $this->getRouter()->generate('admin_match_versus_list'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Admin', $crawler->filter('title')->text());
    }

    /**
     * Test match creation
     *
     * @return null
     */
    public function testMatchCreate()
    {
        $crawler = $this->client->request('GET', $this->getRouter()->generate('admin_match_versus_create'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $action = $crawler->filter('div.sonata-ba-form form')->attr('action');
        $formId = explode('=', $action)[1];

        $form = $crawler->selectButton('btn_create_and_edit')->form();
        $form->setValues(
            array(
                $formId => array(
                  'dateMatch' => [
                    'day' => 23,
                    'month' => 9,
                    'year' => 2017,
                  ],
                  'tableNumber' => 23,
                  'team1' => 1,
                  'team2' => 2,
                ),
            )
        );
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('L\'élément "Match 2017-09-23" a été créé avec succès.', $crawler->filter('div.alert-success')->text());
    }

    /**
     * Test match edition
     *
     * @return null
     */
    public function testMatchEdit()
    {
        /** @var Versus $match */
        $match = $this->fixtures->getReference('match0');

        $crawler = $this->client->request('GET', $this->getRouter()->generate(
            'admin_match_versus_edit',
            ['id' => $match->getId()]
        ));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $action = $crawler->filter('div.sonata-ba-form form')->attr('action');
        $formId = explode('=', $action)[1];

        $form = $crawler->selectButton('btn_update_and_list')->form();
        $form->setValues(array($formId => array('tableNumber' => 42)));
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('L\'élément "Match '.$match->getDateMatch()->format('Y-m-d').'" a été mis à jour avec succès.', $crawler->filter('div.alert-success')->text());
    }

    /**
     * Test match deletion
     *
     * @return null
     */
    public function testMatchDelete()
    {
        /** @var Versus $match */
        $match = $this->fixtures->getReference('match0');

        $crawler = $this->client->request('GET', $this->getRouter()->generate(
            'admin_match_versus_delete',
            ['id' => $match->getId()]
        ));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Êtes-vous sûr de vouloir supprimer l\'élément "Match '.$match->getDateMatch()->format('Y-m-d').'" sélectionné?', $crawler->filter('div.box-body')->text());

        $form = $crawler->filter('button:contains("supprimer")')->eq(0)->form();
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('L\'élément "Match '.$match->getDateMatch()->format('Y-m-d').'" a été supprimé avec succès.', $crawler->filter('div.alert-success')->text());
    }
}
