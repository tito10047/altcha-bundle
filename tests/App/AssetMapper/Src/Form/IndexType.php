<?php

namespace Huluti\AltchaBundle\Tests\App\AssetMapper\Src\Form;


use Huluti\AltchaBundle\Type\AltchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class IndexType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add("altcha",AltchaType::class);
    }


}
