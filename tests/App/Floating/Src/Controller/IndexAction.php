<?php

namespace Tito10047\AltchaBundle\Tests\App\Floating\Src\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Tito10047\AltchaBundle\Tests\App\Floating\Src\Form\IndexType;

class IndexAction extends AbstractController{


    #[Route('/', name: 'index')]
    public function __invoke()
    {

        return $this->render('index.html.twig',[
            "form"=> $this->createForm(IndexType::class)
        ]);
    }

}
