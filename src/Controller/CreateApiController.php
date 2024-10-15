<?php

namespace App\Controller;

use App\Entity\CreatedApi;
use App\Form\CreateApiType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class CreateApiController extends AbstractController
{
    #[Route('/createdApi/home', name: 'app_create_api_home')]
    public function index()
    {
        return $this->render('create_api/index.html.twig');
    }

    #[Route('/create/api', name: 'app_create_api')]
    public function create(Request $request, EntityManagerInterface $manager)
    {
        $createdApi = new CreatedApi();
        $form = $this->createForm(CreateApiType::class, $createdApi);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $createdApi->setApiKey(hash('sha256', $createdApi->getApiKey()));
            $createdApi->setCreator($this->getUser()->getProfile());
            $manager->persist($createdApi);
            $manager->flush();
            return $this->redirectToRoute('app_create_api_home');
        }
        return $this->render('create_api/create.html.twig', ["createApiForm" => $form->createView()]);
    }
}