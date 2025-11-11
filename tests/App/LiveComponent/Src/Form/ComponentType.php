<?php

namespace Tito10047\AltchaBundle\Tests\App\LiveComponent\Src\Form;


use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Tito10047\AltchaBundle\Type\AltchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class ComponentType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
			->add("name",TextType::class,[
				"label"=>"Name",
				"constraints"=>[
					new Length(min:5,max:100),
					new NotBlank()
				]
			])
			->add("altcha",AltchaType::class)
			->add("submit",SubmitType::class,[])
		;
    }


}
