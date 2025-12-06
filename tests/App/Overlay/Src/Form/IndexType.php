<?php

namespace Tito10047\AltchaBundle\Tests\App\Overlay\Src\Form;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Tito10047\AltchaBundle\Type\AltchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class IndexType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
			->add("altcha",AltchaType::class)
			->add("submit",SubmitType::class,[
				"label"=>"Submit"
			])
		;
    }


}
