<?php
/**
 * VersusAdmin class file
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

use MatchBundle\Entity\GroupMatch;
use MatchBundle\Entity\Score;
use MatchBundle\Entity\Versus;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

/**
 * Class VersusAdmin
 *
 * @category Admin
 * @package  CoreBundle\Admin
 * @author   Guillaume <guillaume.torres91@gmail.com>
 * @license  All right reserved
 * @link     Null
 */
class VersusAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'match';
    protected $translationDomain = 'Admin';

    /**
     * @param Versus $object Match object
     *
     * @return string
     */
    public function toString($object)
    {
        return 'Match '.$object->getDateMatch()->format('Y-m-d');
    }

    /**
     * @param GroupMatch $object Student
     */
    public function prePersist($object)
    {
        $entityManager = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
        $team1 = $this->getForm()->get('team1')->getData();
        $team2 = $this->getForm()->get('team2')->getData();

        $score1 = new Score();
        $score1->setTeam($team1);
        $score1->setVersus($object);
        $entityManager->persist($score1);

        $score2 = new Score();
        $score2->setTeam($team2);
        $score2->setVersus($object);
        $entityManager->persist($score2);
    }

    /**
     * @param FormMapper $formMapper Form mapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('dateMatch', DateType::class, array(
                'widget' => 'choice',
            ))
            ->add('tableNumber', NumberType::class)
            ->add('team1', 'entity', array(
                    'class' => 'TeamBundle\Entity\Team',
                    'multiple' => false,
                    'required' => false,
                    'mapped' => false,
                )
            )
            ->add('team2', 'entity', array(
                    'class' => 'TeamBundle\Entity\Team',
                    'multiple' => false,
                    'required' => false,
                    'mapped' => false,
                )
            );
    }

    /**
     * @param DatagridMapper $datagridMapper Datagrid mapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('dateMatch');
    }

    /**
     * @param ListMapper $listMapper List mapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id')
            ->add('dateMatch')
            ->add('tableNumber')
            ->add('team', 'string', array(
                    'label' => 'Teams',
                    'template' => 'CoreBundle:Admin:list_match_team.html.twig',
                )
            );
    }

}