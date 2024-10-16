<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\services\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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



    #[Route('/pay/order', name: 'app_order_pay')]
    public function pay(CartService $cartService): Response
    {
        return $this->render('order/pay.html.twig', [
            "total"=>$cartService->getTotal()
            ]
        );
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
