<?php

declare(strict_types=1);

namespace Tito10047\AltchaBundle\Type;

use Tito10047\AltchaBundle\Validator\Altcha;
use Tito10047\AltchaBundle\Validator\AltchaSentinel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Form\Exception\InvalidConfigurationException;

class AltchaType extends AbstractType
{
    public function __construct(
        private readonly bool $enable,
        private readonly bool $floating,
        private readonly bool $overlay,
        private readonly ?string $overlayContent,
        private readonly bool $useStimulus,
        private readonly bool $hideLogo,
        private readonly bool $hideFooter,
        private readonly string $jsPath,
        private readonly ?string $i18nPath,
        private readonly bool $useSentinel,
		private readonly bool $includeScript,
		private readonly RouterInterface $router,
		private readonly ?string $challengeUrl = null,
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'mapped' => false,
            'required' => false,
            'floating' => null,
            'overlay' => null,
            'overlay_content' => null,
            'hide_logo' => null,
            'hide_footer' => null,
            'attr' => [
                'hidden' => true,
            ],
			'expires'=>'+15 minutes',
			'max_number'=>100000,
            'constraints' => $this->useSentinel ? new AltchaSentinel() : new Altcha(),
            'label' => false,
        ]);

        $resolver->setAllowedTypes('floating', ['null', 'bool']);
        $resolver->setAllowedTypes('overlay', ['null', 'bool']);
        $resolver->setAllowedTypes('overlay_content', ['null', 'string']);
        $resolver->setAllowedTypes('hide_logo', ['null', 'bool']);
        $resolver->setAllowedTypes('hide_footer', ['null', 'bool']);
        $resolver->setAllowedTypes('expires', ['null', 'string']);
        $resolver->setAllowedTypes('max_number', ['null', 'integer']);

        // Validate that "expires" is either null or a valid time expression parsable by strtotime
        $resolver->setNormalizer('expires', static function (Options $options, $value): mixed {
            if (null === $value) {
                return $value;
            }

            if (!\is_string($value) || '' === trim($value) || false === \strtotime($value)) {
                throw new InvalidOptionsException('Invalid time expression for "expires". Use a string parseable by strtotime, e.g., "+15 minutes" or "2025-12-31 23:59:00".');
            }

            return $value;
        });
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if (($options['floating'] ?? false) && ($options['overlay'] ?? false)) {
            throw new InvalidConfigurationException('You must choose betwen floating and overlay modes.');
        }

        $view->vars['enable'] = $this->enable;
        $view->vars['floating'] = $options['floating'] ?? $this->floating;
        $view->vars['overlay'] = $options['overlay'] ?? $this->overlay;
        $view->vars['overlay_content'] = $options['overlay_content'] ?? $this->overlayContent;
        $view->vars['use_stimulus'] = $this->useStimulus;
        $view->vars['hide_logo'] = $options['hide_logo'] ?? $this->hideLogo;
        $view->vars['hide_footer'] = $options['hide_footer'] ?? $this->hideFooter;
        $view->vars['js_path'] = $this->jsPath;
        $view->vars['i18n_path'] = $this->i18nPath;
        $view->vars['expires'] = $options['expires'];
        $view->vars['max_number'] = $options['max_number'];
		$view->vars['use_sentinel'] = $this->useSentinel;
		$view->vars['include_script'] = $this->includeScript;
		if ($this->useSentinel){
			$view->vars['challenge_url'] = $this->challengeUrl;
		}else{
			try {
				$view->vars['challenge_url'] = $this->router->generate('altcha_challenge');
			}catch (RouteNotFoundException $e){
				throw new RouteNotFoundException('The route "altcha_challenge" is not defined. Please add "@AltchaBundle/config/routes.yml" to your routes.yml file.', 0, $e);
			}
		}
    }

    #[\Override]
    public function getBlockPrefix(): string
    {
        return 'altcha';
    }

    #[\Override]
    public function getParent(): ?string
    {
        return TextType::class;
    }
}
