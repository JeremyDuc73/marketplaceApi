<?php

namespace App\Controller;

use App\Entity\CreatedApi;
use App\Form\CreateApiType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CreateApiController extends AbstractController
{
    #[Route('/create/api', name: 'app_create_api')]
    public function create()
    {
        $createdApi = new CreatedApi();
        $form = $this->createForm(CreateApiType::class, $createdApi);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('app_home');
        }
        return $this->render('create_api/create.html.twig', [
            "createApiForm" => $form
        ]);
    }
}
