<?php
/**
 * ConfigUserControllerTest class file
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

use CoreBundle\Entity\Config;
use CoreBundle\Tests\BaseTest;

/**
 * ConfigUserControllerTest class
 *
 * @category Test
 * @package  CoreBundle\Tests\Controller
 * @author   @author Guillaume <contact@guillaume-torres.fr>
 * @license  All right reserved
 * @link     Null
 */
class ConfigUserControllerTest extends BaseTest
{

    /**
     * Test user edition
     *
     * @return null
     */
    public function testConfigEdit()
    {
        /** @var Config $config */
        $config = $this->fixtures->getReference('config');

        $crawler = $this->client->request('GET', $this->getRouter()->generate(
            'admin_core_config_edit',
            ['id' => $config->getId()]
        ));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $action = $crawler->filter('div.sonata-ba-form form')->attr('action');
        $formId = explode('=', $action)[1];

        $form = $crawler->selectButton('btn_update_and_edit')->form();
        $form->setValues(array($formId => array(
                'room_number' => '3',
                'level_max' => '4',
            ),
        ));
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('L\'élément "Configuration" a été mis à jour avec succès.', $crawler->filter('div.alert-success')->text());
    }
}
