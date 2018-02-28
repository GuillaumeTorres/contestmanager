<?php
/**
 * ConfigAdmin class file
 *
 * PHP Version 7.0
 *
 * @category Admin
 * @package  CoreBundle\Admin
 * @author   Guillaume <guillaume.torres91@gmail.com>
 * @license  All right reserved
 * @link     Null
 */
namespace CoreBundle\Admin;

use CoreBundle\Entity\Config;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class ConfigAdmin
 *
 * @category Admin
 * @package  CoreBundle\Admin
 * @author   Guillaume <guillaume.torres91@gmail.com>
 * @license  All right reserved
 * @link     Null
 */
class ConfigAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'config';
    protected $translationDomain = 'Admin';

    /**
     * @param Config $object Student object
     *
     * @return string
     */
    public function toString($object)
    {
        return $object instanceof Config
            ? 'Config '.$object->getId()
            : 'Config';
    }

    /**
     * @param FormMapper $formMapper Form mapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('roomNumber', NumberType::class)
            ->add('level_max', NumberType::class);

    }

    /**
     * @param ListMapper $listMapper List mapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name');
        $listMapper
            ->add('roomNumber', NumberType::class)
            ->add('level_max', NumberType::class);
    }

    /**
     * {@inheritdoc}
     *
     * @param RouteCollection $collection Route collection
     *
     * @return null
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create');
    }

}