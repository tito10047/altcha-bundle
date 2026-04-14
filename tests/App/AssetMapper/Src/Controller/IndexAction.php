<?php

namespace Tito10047\AltchaBundle\Tests\App\AssetMapper\Src\Controller;

use Symfony\Component\HttpFoundation\Request;
use Tito10047\AltchaBundle\Tests\App\AssetMapper\Src\Form\IndexType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Annotation\Route as AnnotationRoute;

class IndexAction extends AbstractController{


    #[Route('/', name: 'index')]
    #[AnnotationRoute('/', name: 'index')]
    public function __invoke(Request $request)
    {


		$form = $this->createForm(IndexType::class);

		$form->handleRequest($request);

	    return $this->render('index.html.twig',[
            "form"=> $form
        ]);
    }

}
