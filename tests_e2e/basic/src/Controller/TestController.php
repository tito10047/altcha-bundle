<?php

namespace App\Controller;

use App\Form\TestType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test-bundle', name: 'test_bundle')]
    public function index(): Response
    {
        $form = $this->createForm(TestType::class);

        return $this->render('test.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
