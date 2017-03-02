<?php

namespace MatchBundle\Controller;

use MatchBundle\Entity\Tournament;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use JMS\Serializer\SerializationContext;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\JsonResponse;
use MatchBundle\Entity\Score;

class RestController extends Controller
{
    /**
     * @ApiDoc(
     * section="Matchs",
     * description= "Get all matchs",
     * statusCodes={
     *      200="Returned when successful",
     * }
     * )
    */
    public function matchsAction()  
    {
        $entityManager = $this->getDoctrine()->getManager();
        $matchs = $entityManager->getRepository('MatchBundle:Versus')->findAll();

        if( empty($matchs) ){
            return new JsonResponse('matchs not found', 404);
        }
        return $matchs;
    }

    /**
     * @ApiDoc(
     * section="Groups",
     * description= "Get all groups",
     * statusCodes={
     *      200="Returned when successful",
     * }
     * )
     */
    public function groupsMatchsAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $groups = $entityManager->getRepository('MatchBundle:GroupMatch')->findAll();

        if( empty($groups) ){
            return new JsonResponse('matchs not found', 404);
        }
        //$groups = $groups[0]->__unset('team');
        foreach ($groups as $group){
            $group = $group->__unset('team');
        }

        return $groups;
    }

    /**
     * @ApiDoc(
     * section="Tournois",
     * description= "Get all Tournament",
     * statusCodes={
     *      200="Returned when successful",
     * }
     * )
     */
    public function tournamentsAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $tournament = $entityManager->getRepository('MatchBundle:Tournament')->findAll();

        if( empty($tournament) ){
            return new JsonResponse('matchs not found', 404);
        }
        return $tournament;
    }

     /**
     * @ApiDoc(
     * section="Groups",
     * description= "Get team of a group",
     * requirements={
     *      {
     *          "name"="idGroup",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id Group"
     *      }
     *  },
     * statusCodes={
     *      200="Returned when successful",
     *      404="Returned when the group are not found"
     * }
     * )
    */
    public function getGroupMatchAction($idGroup)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $matchs = $entityManager->getRepository('MatchBundle:GroupMatch')->findBy(array('id' => $idGroup));
        if( empty($matchs) ){
            return new JsonResponse('groups not found', 404);
        }

        return $matchs;
    }
    
    /**
     * @Rest\Get("/matchs/{$idTeam}", requirements={"$idTeam" = "\d+"})
     * @ApiDoc(
     * section="Matchs",
     * description= "Get matchs by id",
     * requirements={
     *      {
     *          "name"="idTeam",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id Team"
     *      }
     *  },
     * statusCodes={
     *      200="Returned when successful",
     *      404="Returned when the matchs are not found"
     * }
     * )
    */
    public function idMatchsAction($idTeam)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $matchs = $entityManager->getRepository('MatchBundle:Versus')->findBy(array('id' => $idTeam));
        if( empty($matchs) ){
            return new JsonResponse('matchs not found', 404);
        }
        
        return $matchs;
    }
    
    /**
     * @Rest\Get("/matchs/team/{idTeam}", requirements={"idTeam" = "\d+"})
     * @ApiDoc(
     * section="Matchs",
     * description= "Get matchs of a team",
     * requirements={
     *      {
     *          "name"="idTeam",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id Team"
     *      }
     *  },
     * statusCodes={
     *      200="Returned when successful",
     *      404="Returned when the matchs are not found"
     * }
     * )
    */
    public function teamMatchsAction($idTeam)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $matchs = $entityManager->getRepository('MatchBundle:Score')->findBy(array('team' => $idTeam));
        if ( empty($matchs) ) {
            return new JsonResponse('matchs not found', 404);
        }
        
        return $matchs;
    }
    
    /**
     * @Rest\Post("/matchs/team/score")
     * @ApiDoc(
     * section="Matchs",
     * description= "Post the score of a team in a match",
     * parameters={
     *      {"name"="id_team", "dataType"="integer", "required"=true, },
     *      {"name"="id_match", "dataType"="integer", "required"=true, },
     *      {"name"="score", "dataType"="integer", "required"=true, }
     * },
     * statusCodes={
     *      200="Returned when successful",
     *      400="Returned when bad request",
     *      404="Returned when match/team not found"
     * }
     * )
    */
    public function scoreTeamMatchAction(Request $request)
    {
        $idTeam = $request->get('id_team');
        $idMatch = $request->get('id_match');
        $scoreV = $request->get('score');
        
        if (!isset($idTeam) || !isset($idMatch) || !isset($scoreV)) {
            $error = 'Missing parameter(s) id_team = '.$idTeam.' & id_match = '.$idMatch.' & score = '.$scoreV;

            return new JsonResponse($error, 400);
        }
        $entityManager = $this->getDoctrine()->getManager();

        $score = $entityManager->getRepository('MatchBundle:Score')->findOneBy(array('team' => $idTeam, 'versus' => $idMatch));
        $score->setScore($scoreV);
        $entityManager->persist($score);

        $scores = $entityManager->getRepository('MatchBundle:Score')->findBy(array('versus' => $idMatch));
        $allFinish = true;
        foreach ($scores as $score) {
            empty($score->getScore()) ? $allFinish = false : $allFinish = true;
        }

        $team = $entityManager->getRepository('TeamBundle:Team')->findOneBy(array('id' => $idTeam));
        $bestScoreTeam = $team->getBestScore();
        if ($scoreV > $bestScoreTeam) {
            $team->setBestScore($scoreV);
            $entityManager->persist($team);
        }
        $match = $entityManager->getRepository('MatchBundle:Versus')->findOneBy(array('id' => $idMatch));
        if ($allFinish) $match->setFinished(true);
        $entityManager->persist($match);
        
        if (!$match || !$team) {
            return new JsonResponse('Ressource(s) not found', 404);
        }
        $entityManager->flush();

        return new JsonResponse('Success', 200); 
    }

    /**
     * @Rest\Get("/score/{idTournament}", requirements={"idTournament" = "\d+"})
     * @ApiDoc(
     * section="Scores",
     * description= "Get score of a Tournaments",
     * requirements={
     *      {
     *          "name"="idTournament",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id Tournaments"
     *      }
     *  },
     * statusCodes={
     *      200="Returned when successful",
     *      404="Returned when the matchs are not found"
     * }
     * )
     */
    public function scoresTournamentsAction($idTournament)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $tournament = $entityManager->getRepository('MatchBundle:Tournament')->findOneBy(array('id' => $idTournament));
        if( empty($tournament) ){
            return new JsonResponse('matchs not found', 404);
        }

        return $tournament;
    }
}
