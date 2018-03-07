<?php
/**
 * TournamentController file
 *
 * PHP Version 7
 *
 * @category Controller
 * @package  CoreBundle\Controller
 * @author   Guillaume <guillaume.torres91@gmail.com>
 * @license  All right reserved
 * @link     Null
 */
namespace CoreBundle\Controller;

use MatchBundle\Entity\Tournament;
use MatchBundle\Entity\Versus;
use MatchBundle\Helper\TournamentManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * DefaultController class
 *
 * @category Controller
 * @package  CoreBundle\Controller
 * @author   Guillaume <guillaume.torres91@gmail.com>
 * @license  All right reserved
 * @link     Null
 * @SuppressWarnings(PHPMD)
 */
class TournamentController extends Controller
{
    /**
     * tournament action
     *
     * @param Request $request request
     *
     * @return RedirectResponse
     */
    public function tournamentAction(Request $request)
    {
        $data = $request->request->get('matchbundle_tournament');
        $tournamentId = $data['tournament'];

        if ($request->request->has('score')) {
            return new RedirectResponse($this->generateUrl('tournament_score', array('idTourament' => $tournamentId)));
        }
        $tournament = $this->container->get('doctrine')->getManager()->getRepository('MatchBundle:Tournament')->findBy(array('id' => $tournamentId))[0];
        $groups = TournamentManager::getTournamentGroup($tournament);
        $nbBestTeam = count($groups);
        $nbMissingTeam = 4 - $nbBestTeam;
        if ($nbBestTeam > 4 && $nbBestTeam < 8) {
            $nbMissingTeam = 8 - $nbBestTeam;
        }
        $bestTeams = TournamentManager::getBestTeams($groups, $nbMissingTeam);
        $this->createFinalMatchs($bestTeams, $tournament);

        return new RedirectResponse('/');
    }

    /**
     * @param array      $bestTeams
     * @param Tournament $tournament
     */
    private function createFinalMatchs($bestTeams, $tournament)
    {
        $nbTeams = count($bestTeams);
        $nbMatchs = $nbTeams - 1;
        $time = $this->container->getParameter('final_hour_start');
        $entityManager = $this->container->get('doctrine')->getManager();

        $parameters = [
          'changeStep' => $nbTeams / 2,
          'column' => 1,
          'changeColumn' => $nbTeams / 4,
          'tableNumber' => 1,
          'date' => TournamentManager::formatDate($tournament->getDate(), $time),
        ];

        if ($nbTeams == 4) {
            $parameters['step'] = 2;
            $parameters['changeColumn'] = [2];
        } else {
            $parameters['step'] = 3;
            $parameters['changeColumn'] = [1, 2, 5];
        }

        for ($i = 1; $i <= $nbMatchs; $i++) {
            $this->setColumn($parameters, $i, $nbMatchs);

            $match = new Versus();
            $match->setTournament($tournament);
            $match->setDateMatch($parameters['date']);
            $match->setFinalGroup($parameters['column']);
            $match->setTableNumber($parameters['tableNumber']);
            $match->setStep($parameters['step']);

            $entityManager->persist($match);
            $entityManager->flush();

            $parameters['tableNumber']++;

            $this->setStep($parameters, $i);
        }
    }

    /**
     * @param array   $parameters
     * @param integer $cpt
     * @param integer $nbMatchs
     */
    private function setColumn(&$parameters, $cpt, $nbMatchs)
    {
        $parameters['column'] = in_array($cpt, $parameters['changeColumn']) ? 1 : 2;
        if ($cpt == $nbMatchs) {
            $parameters['column'] = null;
        }
    }

    /**
     * @param array   $parameters
     * @param integer $cpt
     */
    private function setStep(&$parameters, $cpt)
    {
        if ($cpt == $parameters['changeStep']) {
            $parameters['step']--;
            $parameters['changeStep'] = $parameters['changeStep'] + ($parameters['changeStep'] / 2);
            $parameters['tableNumber'] = 0;
            $parameters['date'] = $parameters['date']->modify('+5 minutes');
        }
    }
}
