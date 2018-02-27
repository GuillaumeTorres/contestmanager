<?php
/**
 * RoleAdmin class file
 *
 * PHP Version 7.0
 *
 * @category Admin
 * @package  CoreBundle\Admin
 * @author   Louis <louis.richer@ynov.com>
 * @license  All right reserved
 * @link     Null
 */
namespace CoreBundle\Admin;

use CoreBundle\Entity\Role;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class RoleAdmin
 *
 * @category Admin
 * @package  CoreBundle\Admin
 * @author   Louis <louis.richer@ynov.com>
 * @license  All right reserved
 * @link     Null
 */
class RoleAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'role';
    protected $translationDomain = 'Admin';

    /**
     * @param Config $object Student object
     *
     * @return string
     */
    public function toString($object)
    {
        return $object instanceof Role
            ? $object->getName()
            : 'Role';
    }

    /**
     * @param FormMapper $formMapper Form mapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('name', TextType::class);
    }

    /**
     * @param DatagridMapper $datagridMapper Datagrid mapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name');
    }

    /**
     * @param ListMapper $listMapper List mapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name');
    }



}