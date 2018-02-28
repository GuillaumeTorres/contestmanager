<?php
/**
 * DefaultController file
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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use MatchBundle\Helper\TournamentManager;

/**
 * DefaultController class
 *
 * @category Controller
 * @package  CoreBundle\Controller
 * @author   Guillaume <guillaume.torres91@gmail.com>
 * @license  All right reserved
 * @link     Null
 */
class DefaultController extends Controller
{
    /**
     * Mobile app action
     *
     * @param Request $request
     *
     * @return Response
     */
    public function mobileAppUploadAction(Request $request)
    {
        $form = $this->createFormBuilder()
          ->add('appAndroid', FileType::class, array('required' => false))
          ->add('appIos', FileType::class, array('required' => false))
          ->getForm();

        if ($request->isMethod('GET')) {
            return $this->render('CoreBundle:Default:app_upload.html.twig', array('form' => $form->createView()));
        }

        foreach ($request->files as $uploadedFiles) {
            /** @var UploadedFile $uploadedFile */
            foreach ($uploadedFiles as $uploadedFile) {
                if ($uploadedFile) {
                    $directory = $this->container->get('kernel')->getRootDir().'/../web/upload';
                    if (!is_dir($directory)) {
                        mkdir($directory);
                    }
                    $uploadedFile->move($directory, $uploadedFile->getClientOriginalName());
                }
            }
        }

        return $this->render('CoreBundle:Default:app_upload.html.twig', array(
              'success' => 'File uploaded',
              'form' => $form->createView(),
        ));
    }

    /**
     * Mobile app action
     *
     * @param Request $request
     *
     * @return BinaryFileResponse
     */
    public function mobileAppDownloadAction(Request $request)
    {
        $device = $request->query->get('device');
        $version = $request->query->get('version');

        if (!$device) {
            return $this->render('CoreBundle:Default:app_download.html.twig');
        }

        $extenxion = $device == 'android' ? 'apk' : 'ios';
        $filePath = $this->container->get('kernel')->getRootDir().'/../web/upload/ContestManager-'.$version.'.'.$extenxion;

        if (!is_file($filePath)) {
            return $this->render('CoreBundle:Default:app_download.html.twig', array('error' => 'File ContestManager.'.$extenxion.' not found'));
        }
        $response = new BinaryFileResponse($filePath);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'ContestManager.'.$extenxion);

        return $response;
    }

    /**
     * Mobile app action
     *
     * @param string $idTourament
     *
     * @return Response
     */
    public function tournamentScoreAction($idTourament)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $tournament = $entityManager->getRepository('MatchBundle:Tournament')->findOneBy(array('id' => $idTourament));

        if (empty($tournament)) {
            return $this->render('CoreBundle:Default:scores_tournament.html.twig', array('error' => 'Ce tournois n\'existe pas', 'scores' => 'nop'));
        }

        $groups = TournamentManager::getTournamentGroup($tournament);

        return $this->render('CoreBundle:Default:scores_tournament.html.twig', array('scores' => $groups));
    }

    /**
     * Import action
     *
     * @param Request $request
     *
     * @return Response
     */
    public function importAction(Request $request)
    {
        foreach ($request->files as $uploadedFiles) {
            /** @var UploadedFile $uploadedFile */
            foreach ($uploadedFiles as $uploadedFile) {
                $this->get('app.excel.parser')->parseExcelFile($uploadedFile->getPathname());
            }
        }

        return $this->redirectToRoute('sonata_admin_dashboard');
    }

    /**
     * Config action
     *
     * @return Response
     */
    public function configAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $config = $entityManager->getRepository('CoreBundle:Config')->findOneBy(array('id' => 1));

        if (empty($config)) return $this->render('CoreBundle:Admin:standard_layout.html.twig', array('error' => 'Cette config n\'existe pas', 'config' => 'nop'));
        return new RedirectResponse('/config/'.$config->getId().'/edit');//$this->render('CoreBundle:Admin:standard_layout.html.twig', array('config' => $config));
    }
}
