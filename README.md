A simple package to help integrate Altcha on Symfony Form.
======================

<!-- [![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-green)](https://php.net/)
[![Minimum Symfony Version](https://img.shields.io/badge/symfony-%3E%3D%205.4-green)](https://symfony.com)
[![GitHub release](https://img.shields.io/github/v/release/huluti/altcha-bundle)](https://github.com/huluti/altcha-bundle/releases)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=huluti_altcha-bundle&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=huluti_altcha-bundle) -->

This packages provides helper for setting up and validating Altcha CAPTCHA responses.

## Support

- Symfony 6.4+
- PHP 8.1+

## Installation

You can install the package via Composer:

```bash
composer require huluti/altcha-bundle
```

Add bundle into config/bundles.php file :

```php
Huluti\AltchaBundle\HulutiAltchaBundle::class => ['all' => true]
```
Add a config file into config/packages/huluti_altcha.yaml : 

```yaml
huluti_altcha:
  enable: true
  hmacKey: RANDOM_SECRET_KEY
```

### Use with your Symfony Form

Create a form type and insert an Altcha Type to add a Altcha : 

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
            ->add('security', AltchaType::class, ['label' => false])
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

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Thanks

Largely based on: https://github.com/Pixel-Open/cloudflare-turnstile-bundle