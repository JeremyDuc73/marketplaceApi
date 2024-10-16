<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Price;
use Stripe\Product;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    #[Route('/order', name: 'app_order')]
    public function index(): Response
    {
        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
        ]);
    }

    #[Route('/createpaymentlink', name: 'app_order_create_payment_link')]
    public function createPaymentLink(Request $request, CartService $cartService): Response
    {
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        $total = $cartService->getTotal()*100;

        $product = Product::create([
            'name' => 'product',
            'description' => 'clé api',
        ]);

        $price = Price::create([
            'unit_amount' => $total,
            'currency' => 'eur',
            'product' => $product->id,
        ]);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $price->id,
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => 'http://localhost:8000/make/order',
            'cancel_url' => 'http://localhost:8000/cart',
        ]);
        return $this->json(['url' => $session->url]);
    }

    #[Route('/placeOrder', name: 'app_order_place_order')]
    public function pay(CartService $cartService): Response
    {
        return $this->redirectToRoute("app_order_create_payment_link", []);
    }

    #[Route('/make/order', name: 'app_order_make')]
    public function makeOrder(CartService $cartService, EntityManagerInterface $manager): Response{
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $order = new Order();
        $order->setOfProfile($this->getUser()->getProfile());
        $order->setTotal($cartService->getTotal());
        $manager->persist($order);
        foreach ($cartService->getCart() as $cartItem){
            $orderItem = new OrderItem();
            $orderItem->addCreatedApi($cartItem['createdApi']);
            $orderItem->setQuantity($cartItem['quantity']);
            $orderItem->setOfOrder($order);
            $manager->persist($orderItem);
        }
        $manager->flush();
        $cartService->emptyCart();
        $this->addFlash('success', 'order confirmed');
        return $this->redirectToRoute('app_home');
    }
}
