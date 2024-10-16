<?php

namespace App\Service;

use App\Entity\CreatedApi;
use App\Repository\CreatedApiRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private \Symfony\Component\HttpFoundation\Session\SessionInterface $session;
    private CreatedApiRepository $createdApiRepo;
    public function __construct(RequestStack $requestStack, CreatedApiRepository $createdApiRepo){
        $this->session = $requestStack->getSession();
        $this->createdApiRepo = $createdApiRepo;
    }
    public function getCart(): array
    {
        $cart = $this->session->get('cart', []);
        $entityCart = [];

        foreach ($cart as $createdApiId=>$quantity)
        {
            $item = [
                'createdApi'=>$this->createdApiRepo->find($createdApiId),
                'quantity'=>$quantity
            ];

            $entityCart[]=$item;
        }
        return $entityCart;
    }

    public function addApi(CreatedApi $createdApi, $quantity){
        $cart = $this->session->get('cart', []);

        if (isset($cart[$createdApi->getId()])){
            $cart[$createdApi->getId()] = $cart[$createdApi->getId()]+$quantity;
        }else{
            $cart[$createdApi->getId()]=$quantity;
        }
        $this->session->set('cart', $cart);
    }

    public function getTotal(){
        $total = 0;

        foreach ($this->getCart() as $item){
            $total += $item['createdApi']->getPrice() * $item['quantity'];
        }
        return $total;
    }

    public function removeCreatedApi(CreatedApi $createdApi, $quantity){
        $cart = $this->session->get('cart', []);
        $createdApiId = $createdApi->getId();

        if(isset($cart[$createdApiId])){
            $cart[$createdApiId]--;
            if ($cart[$createdApiId]===0){
                unset($cart[$createdApiId]);
            }
        }
        $this->session->set('cart', $cart);
    }

    public function emptyCart(){
        $this->session->remove('cart');
    }

    public function removeRow(CreatedApi $createdApi){
        $cart = $this->session->get('cart', []);
        $createdApiId = $createdApi->getId();

        if (isset($cart[$createdApiId])){
            unset($cart[$createdApiId]);
        }
        $this->session->set('cart', $cart);
    }
}