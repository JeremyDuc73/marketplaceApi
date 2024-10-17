<?php

namespace App\Controller;

use App\Entity\PurchasedApi;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PurchasedApiController extends AbstractController
{
    #[Route('/purchased/api/{id}/generate-api-key', name: 'app_purchased_api_generate_api_key')]
    public function generateApiKey(PurchasedApi $purchasedApi, EntityManagerInterface $manager, MailService $service): Response
    {
        $apiKey = bin2hex(random_bytes(16));

        if ($this->getUser()->getEmail()){
            $service->sendEmail(
                $this->getUser()->getEmail(),
                "Your Api Key DO NOT SHARE !",
                $apiKey
            );
        }


        $apiKey = hash('sha256', $apiKey);

        //ENVOYER PAR REQUETE A L'API

        $purchasedApi->setApiKeyGenerated(true);
        $manager->flush();

        return $this->redirectToRoute('app_profile');
    }

    #[Route('/purchased/api/{id}/delete-api-key', name: 'app_purchased_api_delete_api_key')]
    public function deleteApiKey(PurchasedApi $purchasedApi, EntityManagerInterface $manager): Response
    {

        //ENLEVER LA CLE SUR L'API

        $purchasedApi->setApiKeyGenerated(false);
        $manager->flush();

        return $this->redirectToRoute('app_profile');
    }
}
