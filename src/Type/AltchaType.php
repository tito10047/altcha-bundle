<?php

declare(strict_types=1);

namespace Huluti\AltchaBundle\Type;

use Huluti\AltchaBundle\Validator\Altcha;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AltchaType extends AbstractType
{
    public function __construct(
        private readonly bool $enable,
        private readonly bool $floating,
        private readonly bool $useStimulus,
        private readonly bool $hideLogo,
        private readonly bool $hideFooter
    ) {
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'mapped' => false,
            "required"=>false,
            "attr"=>[
                "hidden"=>true
            ],
            'constraints' => new Altcha(),
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['enable'] = $this->enable;
        $view->vars['floating'] = $this->floating;
        $view->vars['use_stimulus'] = $this->useStimulus;
        $view->vars['hide_logo'] = $this->hideLogo;
        $view->vars['hide_footer'] = $this->hideFooter;
    }

    public function getBlockPrefix(): string
    {
        return 'altcha';
    }

    public function getParent(): ?string
    {
        return TextType::class;
    }
}
