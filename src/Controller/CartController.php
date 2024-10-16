<?php

namespace App\Controller;

use App\Entity\CreatedApi;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cartService->getCart(),
        ]);
    }

    #[Route('/addtocart/{id}/{quantity}', name: 'app_cart_add')]
    public function addToCart(CreatedApi $createdApi, $quantity, CartService $cartService): Response
    {
        $cartService->addApi($createdApi, $quantity);
        return $this->redirectToRoute('app_home');
    }

    #[Route('/removeOneFromCart/{id}', name: 'app_cart_remove_one_from_cart')]
    public function removeOne(CreatedApi $createdApi, CartService $cartService): Response
    {
        $cartService->removeCreatedApi($createdApi, 1);
        return $this->redirectToRoute('app_cart');
    }
    #[Route('/addonetocart/{id}', name: 'app_cart_add_one_to_cart')]
    public function addOne(CreatedApi $createdApi, CartService $cartService): Response
    {
        $cartService->addApi($createdApi, 1);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/removerow/{id}', name: 'app_cart_remove_row')]
    public function removeRow(CartService $cartService, CreatedApi $createdApi): Response{
        $cartService->removeRow($createdApi);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/empty', name: 'app_cart_empty')]
    public function emptyCart(CartService $cartService): Response
    {
        $cartService->emptyCart();
        return $this->redirectToRoute('app_cart');
    }
}
