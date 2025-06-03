<?php

declare(strict_types=1);

namespace Huluti\AltchaBundle\Type;

use Huluti\AltchaBundle\Validator\Altcha;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class AltchaType extends AbstractType
{
    public function __construct(
        private readonly bool $enable,
        private readonly bool $floating,
        private readonly bool $useStimulus,
        private readonly bool $useAssetMapper,
        private readonly bool $hideLogo,
        private readonly bool $hideFooter,
        private readonly string $jsPath,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'mapped' => false,
            'required' => false,
            'floating' => null,
            'hide_logo' => null,
            'hide_footer' => null,
            'attr' => [
                'hidden' => true,
            ],
            'constraints' => new Altcha(),
            'label' => false,
        ]);

        $resolver->setAllowedTypes('floating', ['null', 'bool']);
        $resolver->setAllowedTypes('hide_logo', ['null', 'bool']);
        $resolver->setAllowedTypes('hide_footer', ['null', 'bool']);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['enable'] = $this->enable;
        $view->vars['floating'] = $options['floating'] ?? $this->floating;
        $view->vars['use_stimulus'] = $this->useStimulus;
        $view->vars['use_asset_mapper'] = $this->useAssetMapper;
        $view->vars['hide_logo'] = $options['hide_logo'] ?? $this->hideLogo;
        $view->vars['hide_footer'] = $options['hide_footer'] ?? $this->hideFooter;
        $view->vars['js_path'] = $this->jsPath;
        $view->vars['strings'] = [
            'ariaLinkLabel' => $this->translator->trans('ariaLinkLabel', [], 'altcha'),
            'error' => $this->translator->trans('error', [], 'altcha'),
            'expired' => $this->translator->trans('expired', [], 'altcha'),
            'footer' => $this->translator->trans('footer', [], 'altcha'),
            'label' => $this->translator->trans('label', [], 'altcha'),
            'verified' => $this->translator->trans('verified', [], 'altcha'),
            'verifying' => $this->translator->trans('verifying', [], 'altcha'),
            'waitAlert' => $this->translator->trans('waitAlert', [], 'altcha'),
        ];
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
