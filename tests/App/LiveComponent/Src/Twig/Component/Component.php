<?php

namespace Tito10047\AltchaBundle\Tests\App\LiveComponent\Src\Twig\Component;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Tito10047\AltchaBundle\Tests\App\LiveComponent\Src\Form\ComponentType;
use Tito10047\AltchaBundle\Tests\App\LiveComponent\Src\Twig\EntityManagerInterface;
use Tito10047\AltchaBundle\Tests\App\LiveComponent\Src\Twig\LiveAction;

#[AsLiveComponent]
class Component extends AbstractController {
	use DefaultActionTrait;
	use ComponentWithFormTrait;

	#[LiveAction]
	public function save(EntityManagerInterface $entityManager)
	{
		$this->submitForm();

		$this->resetForm();
	}
	protected function instantiateForm(): FormInterface
	{
		return $this->createForm(ComponentType::class);
	}
}