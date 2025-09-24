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
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RouterInterface;

class AltchaType extends AbstractType
{
    public function __construct(
        private readonly bool $enable,
        private readonly bool $floating,
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
            'hide_logo' => null,
            'hide_footer' => null,
            'attr' => [
                'hidden' => true,
            ],
            'constraints' => $this->useSentinel ? new AltchaSentinel() : new Altcha(),
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
        $view->vars['hide_logo'] = $options['hide_logo'] ?? $this->hideLogo;
        $view->vars['hide_footer'] = $options['hide_footer'] ?? $this->hideFooter;
        $view->vars['js_path'] = $this->jsPath;
        $view->vars['i18n_path'] = $this->i18nPath;
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

    public function getBlockPrefix(): string
    {
        return 'altcha';
    }

    public function getParent(): ?string
    {
        return TextType::class;
    }
}
