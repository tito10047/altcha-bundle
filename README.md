A simple package to help integrate ALTCHA on Symfony Form.
======================

![Packagist Version](https://img.shields.io/packagist/v/huluti/altcha-bundle)
![Packagist License](https://img.shields.io/packagist/l/huluti/altcha-bundle)
![Packagist Downloads](https://img.shields.io/packagist/dt/huluti/altcha-bundle)

This packages integrates [ALTCHA](https://altcha.org/), a privacy-friendly Captcha alternative, with Symfony forms.
Simply add an `AltchaType` field to your form and this package will automatically check the challenge issue. 

> ALTCHA uses a proof-of-work mechanism to protect your website, APIs, and online services from spam and unwanted content.
> 
>Unlike other solutions, ALTCHA is free, open-source and self-hosted, does not use cookies nor fingerprinting, does not track users, and is fully compliant with GDPR.
>
> Say goodbye to tedious puzzle-solving and improve your website's UX by integrating a fully automated proof-or-work mechanism.

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
  hmacKey: 'RANDOM_SECRET_KEY'
  floating: true
```

Import routes:

```php
$routingConfigurator->import('@HulutiAltchaBundle/config/routes.yml');
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

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Thanks

Largely based on: https://github.com/Pixel-Open/cloudflare-turnstile-bundle