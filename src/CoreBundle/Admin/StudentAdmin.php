<?php
/**
 * StudentAdmin class file
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

use SchoolBundle\Entity\Student;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use UserBundle\Entity\User;

/**
 * Class StudentAdmin
 *
 * @category Admin
 * @package  CoreBundle\Admin
 * @author   Guillaume <guillaume.torres91@gmail.com>
 * @license  All right reserved
 * @link     Null
 */
class StudentAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'student';
    protected $translationDomain = 'Admin';

    /**
     * @param Student $object Student object
     *
     * @return string
     */
    public function toString($object)
    {
        return $object instanceof Student
            ? $object->getFirstName().' '.$object->getLastName()
            : 'Student';
    }
    
    /**
     * @param Student $object Student
     */
    public function prePersist($object) {
        /** @var User $user */
        $user = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
        $roles = $user->getRoles();

        in_array('ROLE_SUPER_ADMIN', $roles) ?
          $object->setSchool($object->getTeam()->getGroup()->getTeacher()->getSchool()) :
          $object->setSchool($user->getSchool());
    }

    /**
     * @param FormMapper $formMapper Form mapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            //->add('level', IntegerType::class)
            ->add('team', 'entity', array(
                    'class' => 'TeamBundle\Entity\Team',
                    'multiple' => false,
                    'required' => false,
                )
            )
            ->add('role', 'entity', array(
                    'class' => 'CoreBundle\Entity\Role',
                    'multiple' => false,
                    'required' => false,
                )
            ); //*
    }

    /**
     * @param DatagridMapper $datagridMapper Datagrid mapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('firstName')
            ->add('lastName')
            ->add('team')
            ->add('school')
            //->add('level')
            ->add('role') //*
        ;
    }

    /**
     * @param ListMapper $listMapper List mapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('firstName');
        $listMapper
            ->add('lastName', TextType::class)
            ->add('team')
            ->add('school')
            ->add('role');  //*
    }

    public function configureActionButtons($action, $object = null)
    {
        $list = parent::configureActionButtons($action, $object);

        $list['import'] = array(
            'template' =>  'CoreBundle:Admin:import_button.html.twig'
        );

        return $list;
    }
}