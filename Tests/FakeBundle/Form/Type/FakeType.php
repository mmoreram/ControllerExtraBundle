<?php

/*
 * This file is part of the ControllerExtraBundle for Symfony2.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FakeType.
 */
class FakeType extends AbstractType
{
    /**
     * Default form options.
     *
     * @param OptionsResolver $resolver
     *
     * @return array With the options
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Mmoreram\ControllerExtraBundle\Tests\FakeBundle\Entity\Fake',
        ]);
    }

    /**
     * Buildform function.
     *
     * @param FormBuilderInterface $builder the formBuilder
     * @param array                $options the options for this form
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('field', 'Symfony\Component\Form\Extension\Core\Type\TextType');
    }

    /**
     * Return unique name for this form.
     *
     * @return string
     */
    public function getName()
    {
        return 'fake_form_type';
    }
}
