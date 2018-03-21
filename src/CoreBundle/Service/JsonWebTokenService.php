<?php
/**
 * JsonWebTokenService file
 *
 * PHP Version 7
 *
 * @category Service
 *
 * @package  CoreBundle\Service
 */

namespace CoreBundle\Service;

use Doctrine\ORM\EntityManager;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;

/**
 * JsonWebTokenService class
 */
class JsonWebTokenService
{
    private $jwtEncoder;
    private $entityManager;

    /**
     * JsonWebTokenService constructor.
     *
     * @param JWTEncoderInterface $jwtEncoder
     * @param EntityManager       $entityManager
     */
    public function __construct(JWTEncoderInterface $jwtEncoder, EntityManager $entityManager)
    {
        $this->jwtEncoder = $jwtEncoder;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     *
     * @return null|User
     */
    public function getUser(Request $request)
    {
        $token = $this->getToken($request);
        if (!$token) {
            return null;
        }
        try {
            $data = $this->jwtEncoder->decode($token);
        } catch (\Exception $e) {
            return null;
        }
        $user = $this->entityManager->getRepository('UserBundle:User')->findOneBy([
            'username' => $data['username'],
        ]);

        return $user;
    }

    /**
     * @param Request $request
     *
     * @return array|bool|false|null|string
     */
    private function getToken(Request $request)
    {
        $extractor = new AuthorizationHeaderTokenExtractor(
            'Bearer',
            'Authorization'
        );
        $token = $extractor->extract($request);
        if (!$token) {
            return null;
        }

        return $token;
    }
}