<?php

namespace UserBundle\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\PreAuthenticationJWTUserToken;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializationContext;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Entity\User;

class RestController extends Controller
{
    /**
     * @ApiDoc(
     * section="Users",
     * description= "Get all users",
     * statusCodes={
     *      200="Returned when successful",
     * }
     * )
    */
    public function getUsersAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $users = $entityManager->getRepository('UserBundle:User')->findAll();
        
        return $users;
    }
    
    /**
     * @Rest\Post("/arbiter/login", name="_login")
     *
     * @param Request $request
     *
     * @ApiDoc(
     * section="Users",
     * description= "Arbiter login",
     * parameters={
     *      {"name"="username", "dataType"="string", "required"=true, },
     *      {"name"="password", "dataType"="string", "required"=true, }
     * },
     * statusCodes={
     *      200="Returned when successful",
     *      401="Returned when invalid username/password"
     * }
     * )
     *
     * @return JsonResponse
    */
    public function arbiterLoginAction(Request $request)
    {
        $username = $request->get('username');
        $password = $request->get('password');

        /** @var User $user */
        $user = $this->get('fos_user.user_manager')->findUserBy(array('username' => $username));
        $encoder = $this->get('security.encoder_factory')->getEncoder($user);
        if (!$user || !$encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt())) {
            return new JsonResponse('Invalid username/password', 401);
        }
        $jsonUser = $user->jsonSerialize();

        $token = $this->get('lexik_jwt_authentication.encoder')->encode([
            'username' => $user->getUsername(),
        ]);
        $jsonUser['token'] = $token;

        return new JsonResponse($jsonUser, 200, ['Authorization' => 'Bearer '.$token]);
    }
    
    /**
     * @ApiDoc(
     * section="Users",
     * description= "Get user by id",
     * requirements={
     *      {
     *          "name"="idUser",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id user"
     *      }
     *  },
     * statusCodes={
     *      200="Returned when successful",
     *      404="Returned when the user is not found"
     * }
     * )
    */
    public function getUserAction($idUser)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository('UserBundle:User')->findOneBy(array('id' => $idUser));
        if( empty($user) ){
            return new JsonResponse('user not found', 404);
        }
        return $user;
    }
}
