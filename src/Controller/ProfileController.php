<?php

namespace App\Controller;

use App\Service\EncryptorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    private $encryptor;

    public function __construct(EncryptorService $encryptor)
    {
        $this->encryptor = $encryptor;
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        $user = $this->getUser();

        $createdApis = $user->getProfile()->getCreatedApis();

        foreach ($createdApis as $api) {
            $decryptedApiKey = $this->encryptor->decrypt($api->getApiKey());
            $api->setApiKey($decryptedApiKey);
        }

        $purchasedApis = $user->getProfile()->getPurchasedApis();


        return $this->render('profile/index.html.twig', [
            'createdApis' => $createdApis,
            'purchasedApis' => $purchasedApis,
        ]);
    }
}
