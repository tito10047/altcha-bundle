<?php

namespace Tito10047\AltchaBundle\Tests\App\Webpack\Src\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Tito10047\AltchaBundle\Type\AltchaType;

class IndexType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add("altcha",AltchaType::class);
    }


}
