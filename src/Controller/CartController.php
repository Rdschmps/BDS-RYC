<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Article;
use App\Form\CartType;
use App\Service\CartService;
use App\Repository\CartRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/cart')]
final class CartController extends AbstractController
{
    #[Route('/', name: 'app_cart_index', methods: ['GET'])]
    public function cart(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour voir votre panier.');
        }

        $cartItems = $entityManager->getRepository(Cart::class)->findBy(['user' => $user]);

        $articles = [];
        $total = 0;

        foreach ($cartItems as $cartItem) {
            $article = $cartItem->getArticle();

            if ($article) {
                $articles[] = [
                    'id' => $article->getId(),
                    'name' => $article->getName(),
                    'price' => $article->getPrice(),
                    'imageUrl' => $article->getImageUrl() ? 'uploads/articles/' . $article->getImageUrl() : 'assets/default-image.jpg',
                    'quantity' => $cartItem->getQuantity(),
                    'subtotal' => $article->getPrice() * $cartItem->getQuantity(),
                ];
                
                $total += $article->getPrice() * $cartItem->getQuantity();
            }
        }

        return $this->render('cart/cart.html.twig', [
            'articles' => $articles,
            'total' => $total
        ]);
    }

    #[Route('/new', name: 'app_cart_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cart = new Cart();
        $form = $this->createForm(CartType::class, $cart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cart);
            $entityManager->flush();

            return $this->redirectToRoute('app_cart_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cart/new.html.twig', [
            'cart' => $cart,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cart_show', methods: ['GET'])]
    public function show(Cart $cart): Response
    {
        return $this->render('cart/show.html.twig', [
            'cart' => $cart,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cart_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cart $cart, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CartType::class, $cart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cart_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cart/edit.html.twig', [
            'cart' => $cart,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cart_delete', methods: ['POST'])]
    public function delete(Request $request, Cart $cart, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $cart->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cart);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cart_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/add-to-cart/{id}', name: 'add_to_cart', methods: ['GET'])]
    public function addToCart(int $id, CartService $cartService): RedirectResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        $cartService->addToCart($id, 1, $user);

        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/remove-from-cart/{id}', name: 'remove_from_cart', methods: ['POST', 'GET'])]
    public function removeFromCart(int $id, CartService $cartService): RedirectResponse
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour gérer votre panier.');
        }

        $cartService->removeFromCart($id, $user);

        $this->addFlash('success', 'Article supprimé du panier.');
        return $this->redirectToRoute('app_cart_index');
    }
}
