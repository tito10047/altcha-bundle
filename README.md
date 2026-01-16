A simple package to help integrate ALTCHA on Symfony Form.
======================

![Packagist Version](https://img.shields.io/packagist/v/tito10047/altcha-bundle)
![Packagist License](https://img.shields.io/packagist/l/tito10047/altcha-bundle)
![Packagist Downloads](https://img.shields.io/packagist/dt/tito10047/altcha-bundle)
[![Tests](https://github.com/Tito10047/altcha-bundle/actions/workflows/ci.yml/badge.svg)](https://github.com/Tito10047/altcha-bundle/actions/workflows/ci.yml)

This packages integrates [ALTCHA](https://altcha.org/), a privacy-friendly Captcha alternative, with Symfony forms.
Simply add an `AltchaType` field to your form and this package will automatically check the challenge issue. 

> ALTCHA uses a proof-of-work mechanism to protect your website, APIs, and online services from spam and unwanted content.
> 
>Unlike other solutions, ALTCHA is free, open-source and self-hosted, does not use cookies nor fingerprinting, does not track users, and is fully compliant with GDPR.
>
> Say goodbye to tedious puzzle-solving and improve your website's UX by integrating a fully automated proof-of-work mechanism.

## Support

- Symfony 6.4 | 7.4 | 8.0+
- PHP 8.2+
- Webpack | Asset Mapper | Twig

## Installation

You can install the package via Composer:

```bash
composer require tito10047/altcha-bundle
```

Add bundle into config/bundles.php file:

```php
Tito10047\AltchaBundle\AltchaBundle::class => ['all' => true]
```

Add a config file:

### YML

`config/packages/altcha.yaml`

```yml
altcha:
    enable: true
    hmacKey: '%env(APP_SECRET)%'
    floating: true
    overlay: false
    use_stimulus: false
    include_script: true
    hide_logo: false
    hide_footer: false

when@test:
    altcha:
        enable: false
```

### PHP

`config/packages/altcha.php`: 

```php
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('altcha', [
        'enable' => true,
        'hmacKey' => 'RANDOM_SECRET_KEY',
        'floating' => true,
        'overlay' => true,
        'use_stimulus' => false,
        'include_script' => true,
        'hide_logo' => false,
        'hide_footer' => false
    ]);

    if ('test' === $containerConfigurator->env()) {
        // Disable captcha in test environment
        $containerConfigurator->extension('altcha', [
            'enable' => false,
        ]);
    }
};
```

Import bundle routes:

### YML

```yml
altcha:
    resource: '@AltchaBundle/config/routes.yml'
    type: yaml
```

### PHP

```php
$routingConfigurator->import('@AltchaBundle/config/routes.yml');
```

⚠️ **Important – Security Configuration**

If your application restricts access globally using a rule like:

```yaml
access_control:
    - { path: ^/, roles: ROLE_USER }
```

Then the Altcha challenge endpoint (`/altcha/challenge`) will also be protected by default.

To allow it to be publicly accessible (as intended for the challenge mechanism to work), **you must explicitly add the following rule before the global one**:

```yaml
access_control:
    - { path: ^/altcha/challenge, roles: PUBLIC_ACCESS }
    - { path: ^/, roles: ROLE_USER }
```

This ensures that the challenge endpoint is reachable by unauthenticated users, while keeping the rest of your app secure.

### Use with your Symfony Form

Create a form type and insert an AltchaType to add the captcha: 

```php
<?php

namespace App\Form;

use App\Entity\Contact;
use Tito10047\AltchaBundle\Type\AltchaType;
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

### Use with Webpack Encore

```js
//webpack.config.js
module.exports = Encore.getWebpackConfig();
module.exports.resolve.alias["altcha/dist/altcha.i18n.js"]='altcha/i18n';
```
```yaml
#config/packages/altcha.yaml
altcha:
    use_stimulus: true
    include_script: false
```

### Optional: usage with  UX Live components

There is only one option need to be changed to work with or UX Live component.

```yml
altcha:
    use_stimulus: true
    floating: false
    include_script: false
```

### Optional: usage with Sentinel

Configure the package by providing your sentinel instance endpoint and your API key:

```yml
altcha:
    sentinel:
        base_url: 'http://localhost:8080'
        api_key: 'key_xxxxxxxxxxxx'
```

Activating this configuration will have the effect to use the sentinel server to generate a new challenge and for it's verification. 
If the sentinel instance is not reachable by the client or by the server, we will fallback on our local configuration.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.