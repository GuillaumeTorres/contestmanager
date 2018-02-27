<?php
/**
 * TournamentAdmin class file
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

use DateTime;
use MatchBundle\Entity\GroupMatch;
use MatchBundle\Entity\Score;
use MatchBundle\Entity\Tournament;
use MatchBundle\Entity\Versus;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use MatchBundle\Helper\TournamentManager;

/**
 * Class TournamentAdmin
 *
 * @category Admin
 * @package  CoreBundle\Admin
 * @author   Guillaume <guillaume.torres91@gmail.com>
 * @license  All right reserved
 * @link     Null
 */
class TournamentAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'tournament';
    protected $translationDomain = 'Admin';

    protected $halfDay = "";
    protected $tableNumber = 1;

    /**
     * @param Tournament $object Tournament object
     *
     * @return string
     */
    public function toString($object)
    {
        return $object instanceof Tournament
            ? $object->getName()
            : 'Tournament';
    }


    
    /**
     * @param FormMapper $formMapper Form mapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Tournament')
                ->add('name', TextType::class)
                ->add('halfDay', 'choice', array(
                    'choices' => array(
                        'Am' => $this->trans('Morning'),
                        'Pm' => $this->trans('Afternoon'),
                    ),
                ))
                ->add('date', DateType::class, array(
                    'widget' => 'choice',
                ))
            ->end();
        for ($i = 1; $i <= $this->getRoomNumber(); $i++) {
            $formMapper
                ->with('Room '.$i)
                    ->add('group'.$i, 'entity', array(
                            'class' => 'MatchBundle\Entity\GroupMatch',
                            'multiple' => true,
                            'required' => false,
                            'mapped' => false,
                            'label' => 'Groups',
                        )
                    )
                ->end();
        }
    }

    /**
     * @param DatagridMapper $datagridMapper Datagrid mapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('date');
    }

    /**
     * @param ListMapper $listMapper List mapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('date')
            ->add('halfDay');
    }

    /**
     * @param Tournament $object Tournament
     */
    public function prePersist($object) {
        $groupsList = $this->formatGroupsList();
        $this->halfDay = $object->getHalfDay();
        $this->startTournament($groupsList, $object);
    }

    /**
     * Format array groups
     *
     * @return array
     */
    private function formatGroupsList() {
        $groupsList = [];

        for ($i = 1; $i <= $this->getRoomNumber(); $i++) {
            $groupsList[] = $this->getForm()->get('group'.$i)->getData();
        }

        return $groupsList;
    }

    /**
     * Start the tournament
     *
     * @param array $groupsList Groups
     * @param Tournament $tournament Tournament
     */
    private function startTournament($groupsList, $tournament) {
        foreach ($groupsList as $groups) {
            $this->setRandomMatchs($groups, $tournament);
        }
    }

    /**
     * Set random matchs
     *
     * @param array $groups Groups
     * @param Tournament $tournament Tournament
     */
    private function setRandomMatchs($groups, $tournament)
    {
        $times = $this->getConfigurationPool()->getContainer()->getParameter($this->halfDay.'_match_schedule');
        $entityManager = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
        $teams = $this->formatTeamList($groups);
        $matchs = $this->formatTeamMatch($teams);

        foreach ($matchs as $match) {
            foreach ($times as $time) {
                $versus = new Versus();
                $versus->setDateMatch(TournamentManager::formatDate($tournament->getDate(), $time));
                $versus->setTableNumber($this->tableNumber);
                $versus->setTournament($tournament);

                foreach ($match as $matchItem) {
                    $score = new Score();
                    $score->setTeam($matchItem);
                    $score->setVersus($versus);

                    $entityManager->persist($score);
                }
                $entityManager->persist($versus);
                $entityManager->flush();
            }
            $this->tableNumber++;
        }
    }

    /**
     * Format team match
     *
     * @param array $teams Teams
     *
     * @return array
     */
    private function formatTeamMatch($teams)
    {
        $matchs = [];
        $cpt = 0;
        while (count($teams) > 0) {
            $idTeams = [$cpt, $cpt + 1];
            $match = [];

            foreach ($idTeams as $idTeam) {
                if (array_key_exists($idTeam, $teams)) {
                    $match[] = $teams[$idTeam];
                    unset($teams[$idTeam]);
                }
            }
            $matchs[] = $match;
            $cpt += 2;
        }

        return $matchs;
    }

    /**
     * Format team list
     *
     * @param array $groups Groups
     *
     * @return array
     */
    private function formatTeamList($groups)
    {
        $teams = [];

        /** @var GroupMatch $group */
        foreach ($groups as $group) {
            $teamsGroup = $group->getTeam();
            foreach ($teamsGroup as $team) {
                $teams[] = $team;
            }
        }

        return $teams;
    }

    /**
     * Get Room number
     *
     * @return integer
     */
    private function getRoomNumber()
    {
        $entityManager = $this->getConfigurationPool()->getContainer()->get('doctrine')->getManager();
        $config = $entityManager->getRepository('CoreBundle:Config')->findOneBy(array());

        return $config->getRoomNumber();
    }
}