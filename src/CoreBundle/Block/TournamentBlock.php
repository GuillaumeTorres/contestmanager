<?php
/**
 * TournamentBlock file
 *
 * PHP Version 7
 *
 * @category Block
 * @package  CoreBundle\Block
 * @author   Guillaume <guillaume.torres91@gmail.com>
 * @license  All right reserved
 * @link     Null
 */
namespace CoreBundle\Block;

use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * TournamentBlock
 *
 * @category Block
 * @package  CoreBundle\Block
 * @author   Guillaume <guillaume.torres91@gmail.com>
 * @license  All right reserved
 * @link     Null
 */
class TournamentBlock extends BaseBlockService
{
    protected $type;
    protected $templating;
    protected $container;

    /**
     * TournamentBlock constructor.
     *
     * @param string                                                     $type       Type
     * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating Templating
     * @param ContainerInterface                                         $container  Container
     */
    public function __construct($type, $templating, $container)
    {
        $this->type = $type;
        $this->templating = $templating;
        $this->container = $container;

        parent::__construct($type, $templating);
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param BlockContextInterface $blockContext Block interface
     * @param Response|null         $response     Response
     *
     * @return Response
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        /** @var FormFactory $formFactory */
        $formFactory = $this->container->get('form.factory');
        $tournamentForm = $formFactory->create('MatchBundle\Form\TournamentType');
        $importForm = $formFactory->create('CoreBundle\Form\ImportType');

        return $this->renderResponse('CoreBundle:Block:tournament_block.html.twig', array(
            'tournament_form' => $tournamentForm->createView(),
            'import_form'     => $importForm->createView(),
        ));
    }
}
