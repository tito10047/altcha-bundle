A simple package to help integrate ALTCHA on Symfony Form.
======================

![Packagist Version](https://img.shields.io/packagist/v/huluti/altcha-bundle)
![Packagist License](https://img.shields.io/packagist/l/huluti/altcha-bundle)
![Packagist Downloads](https://img.shields.io/packagist/dt/huluti/altcha-bundle)
[![Tests](https://github.com/Huluti/altcha-bundle/actions/workflows/tests.yml/badge.svg)](https://github.com/Huluti/altcha-bundle/actions/workflows/tests.yml)

This packages integrates [ALTCHA](https://altcha.org/), a privacy-friendly Captcha alternative, with Symfony forms.
Simply add an `AltchaType` field to your form and this package will automatically check the challenge issue. 

> ALTCHA uses a proof-of-work mechanism to protect your website, APIs, and online services from spam and unwanted content.
> 
>Unlike other solutions, ALTCHA is free, open-source and self-hosted, does not use cookies nor fingerprinting, does not track users, and is fully compliant with GDPR.
>
> Say goodbye to tedious puzzle-solving and improve your website's UX by integrating a fully automated proof-of-work mechanism.

## Support

- Symfony 6.4+
- PHP 8.1+

## Installation

You can install the package via Composer:

```bash
composer require huluti/altcha-bundle
```

Add bundle into config/bundles.php file:

```php
Huluti\AltchaBundle\HulutiAltchaBundle::class => ['all' => true]
```

Add a config file:

### YML

`config/packages/huluti_altcha.yaml`

```yml
huluti_altcha:
    enable: true
    hmacKey: 'RANDOM_SECRET_KEY'
    floating: true
    use_stimulus: false
    hide_logo: false
    hide_footer: false

when@test:
    huluti_altcha:
        enable: false
```

### PHP

`config/packages/huluti_altcha.php`: 

```php
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('huluti_altcha', [
        'enable' => true,
        'hmacKey' => 'RANDOM_SECRET_KEY',
        'floating' => true,
        'use_stimulus' => false,
        'hide_logo' => false,
        'hide_footer' => false
    ]);

    if ('test' === $containerConfigurator->env()) {
        // Disable captcha in test environment
        $containerConfigurator->extension('huluti_altcha', [
            'enable' => false,
        ]);
    }
};
```

Import bundle routes:

### YML

```yml
huluti_altcha:
    resource: '@HulutiAltchaBundle/config/routes.yml'
    type: yaml
```

### PHP

```php
$routingConfigurator->import('@HulutiAltchaBundle/config/routes.yml');
```

⚠️ **Important – Security Configuration**

If your application restricts access globally using a rule like:

```yaml
access_control:
    - { path: ^/, roles: ROLE_USER }
```

Then the Altcha challenge endpoint (`/huluti_altcha/challenge`) will also be protected by default.

To allow it to be publicly accessible (as intended for the challenge mechanism to work), **you must explicitly add the following rule before the global one**:

```yaml
access_control:
    - { path: ^/huluti_altcha/challenge, roles: PUBLIC_ACCESS }
    - { path: ^/, roles: ROLE_USER }
```

This ensures that the challenge endpoint is reachable by unauthenticated users, while keeping the rest of your app secure.

### Use with your Symfony Form

Create a form type and insert an AltchaType to add the captcha: 

```php
<?php

namespace App\Form;

use App\Entity\Contact;
use Huluti\AltchaBundle\Type\AltchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => false, 'attr' => ['placeholder' => 'name']])
            ->add('message', TextareaType::class, ['label' => false, 'attr' => ['placeholder' => 'message']])
            ->add('security', AltchaType::class, [
                'label' => false,
                'floating' => true,
                'hide_logo' => false,
                'hide_footer' => false,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
```

### Use inside UX Live component

Asset mapper is required to use this package in the UX Live component.

```composer require symfony/asset-mapper```

```php
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('huluti_altcha', [
        'enable' => true,
        'hmacKey' => 'RANDOM_SECRET_KEY',
        'floating' => false,
        'use_stimulus' => true
    ]);
};
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.