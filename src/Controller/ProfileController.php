<?php

namespace App\Controller;

use App\Service\EncryptorService;
use App\Service\RemainingRequestsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProfileController extends AbstractController
{
    private $encryptor;

    public function __construct(EncryptorService $encryptor)
    {
        $this->encryptor = $encryptor;
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(RemainingRequestsService $service, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();

        $createdApis = $user->getProfile()->getCreatedApis();

        foreach ($createdApis as $api) {
            $decryptedApiKey = $this->encryptor->decrypt($api->getApiKey());
            $api->setApiKey($decryptedApiKey);
        }

        $purchasedApis = $user->getProfile()->getPurchasedApis();

//        foreach ($purchasedApis as $api) {
//            $remainingRequests = $service->getRemainingRequests($api);
//            $api->setRemainingRequests($remainingRequests);
//            $manager->flush();
//        }

        return $this->render('profile/index.html.twig', [
            'createdApis' => $createdApis,
            'purchasedApis' => $purchasedApis,
        ]);
    }
}
