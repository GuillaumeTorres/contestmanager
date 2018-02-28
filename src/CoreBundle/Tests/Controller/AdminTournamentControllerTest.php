<?php
/**
 * AdminTournamentControllerTest class file
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
use MatchBundle\Entity\Tournament;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * AdminTournamentControllerTest class
 *
 * @category Test
 * @package  CoreBundle\Tests\Controller
 * @author   @author Guillaume <contact@guillaume-torres.fr>
 * @license  All right reserved
 * @link     Null
 */
class AdminTournamentControllerTest extends BaseTest
{
    /**
     * Test tournament list
     *
     * @return null
     */
    public function testIndex()
    {
        $crawler = $this->client->request('GET', $this->getRouter()->generate('admin_match_tournament_list'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Admin', $crawler->filter('title')->text());
    }

    /**
     * Test tournament creation
     *
     * @return null
     */
    public function testTournamentCreate()
    {
        $crawler = $this->client->request('GET', $this->getRouter()->generate('admin_match_tournament_create'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $action = $crawler->filter('div.sonata-ba-form form')->attr('action');
        $formId = explode('=', $action)[1];

//        $roomNumber = 2;
//        $cpt = 1;
//        $group1 = [];
//        $group2 = [];
//        for ($i = 1; $i <= $roomNumber; $i++) {
//            for ($j = 0; $j < 2; $j++) {
//                ${'group'.$i}[] = $this->fixtures->getReference('group'.$cpt);
//                $cpt++;
//            }
//        }

        $form = $crawler->selectButton('btn_create_and_edit')->form();
        // TODO: Implement group
        $form->setValues(
            array(
                $formId => array(
                  'name' => 'lorem ipsum',
                  'halfDay' => 'Am',
                  'date' => array(/*'date' => [*/
                      'day' => 23,
                      'month' => 9,
                      'year' => 2017
                  /*]*/),
                ),
            )
        );
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('L\'élément "lorem ipsum" a été créé avec succès.', $crawler->filter('div.alert-success')->text());
    }

    /**
     * Test tournament edition
     *
     * @return null
     */
    public function testTournamentEdit()
    {
        /** @var Tournament $tournament */
        $tournament = $this->fixtures->getReference('tournament');

        $crawler = $this->client->request('GET', $this->getRouter()->generate(
            'admin_match_tournament_edit',
            ['id' => $tournament->getId()]
        ));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $action = $crawler->filter('div.sonata-ba-form form')->attr('action');
        $formId = explode('=', $action)[1];

        $form = $crawler->selectButton('btn_update_and_list')->form();
        $form->setValues(array($formId => array('name' => 'new tournament')));
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('L\'élément "new tournament" a été mis à jour avec succès.', $crawler->filter('div.alert-success')->text());
    }

    /**
     * Test tournament deletion
     *
     * @return null
     */
    public function testGroupDelete()
    {
        /** @var GroupMatch $group */
        $group = $this->fixtures->getReference('group1');

        $crawler = $this->client->request('GET', $this->getRouter()->generate(
            'admin_match_groupmatch_delete',
            array('id' => $group->getId())
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
