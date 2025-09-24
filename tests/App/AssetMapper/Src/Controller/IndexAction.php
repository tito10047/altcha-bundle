<?php

namespace Tito10047\AltchaBundle\Tests\App\AssetMapper\Src\Controller;

use Tito10047\AltchaBundle\Tests\App\AssetMapper\Src\Form\IndexType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class IndexAction extends AbstractController{


    #[Route('/', name: 'index')]
    public function __invoke()
    {

        return $this->render('index.html.twig',[
            "form"=> $this->createForm(IndexType::class)
        ]);
    }

}
