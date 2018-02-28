<?php
/**
 * ImportType file
 *
 * PHP Version 7
 *
 * @category Block
 * @package  CoreBundle\Form
 * @author   Guillaume <guillaume.torres91@gmail.com>
 * @license  All right reserved
 * @link     Null
 */
namespace CoreBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * ImportType class
 *
 * @category Block
 * @package  MatchBundle\Form
 * @author   Guillaume <guillaume.torres91@gmail.com>
 * @license  All right reserved
 * @link     Null
 */
class ImportType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('file', FileType::class, array(
                'label' => 'File to upload',
                'translation_domain' => 'Admin',
          ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'corebundle_import';
    }
}
