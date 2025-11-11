<?php

namespace Tito10047\AltchaBundle\Tests\App\LiveComponent\Src\Twig\Component;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Tito10047\AltchaBundle\Tests\App\LiveComponent\Src\Form\ComponentType;

#[AsLiveComponent]
class Component extends AbstractController {
	use DefaultActionTrait;
	use ComponentWithFormTrait;

	public ?string $message = null;
	#[LiveAction]
	public function save()
	{
		$this->submitForm();

		$this->resetForm();
		$this->message = 'Saved!';
	}
	protected function instantiateForm(): FormInterface
	{
		return $this->createForm(ComponentType::class);
	}
}