<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Article;
use App\Form\CartType;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\CartService;
use App\Repository\ArticleRepository;


use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/cart')]
final class CartController extends AbstractController
{
    #[Route('/', name: 'app_cart_index', methods: ['GET'])]
    public function cart(CartService $cartService, ArticleRepository $articleRepository): Response
    {
        $useFakeData = false; // Active les données fictives 
        $cart = $cartService->getCart();
        $articles = [];
        $total = 0;
    
        foreach ($cart as $articleId => $quantity) {
            if ($useFakeData) {
                // Produit fictif (sans base de données)
                $article = [
                    'id' => $articleId,
                    'name' => "Article $articleId",
                    'price' => 1000, // 10€
                    'imageUrl' => 'assets/default-image.jpg',
                    'quantity' => $quantity,
                    'subtotal' => 1000 * $quantity
                ];
                $articles[] = $article;
                $total += $article['subtotal'];
            } else {
                $article = $articleRepository->find($articleId);
    
                if ($article) {
                    $imageUrl = $article->getImageUrl() ? 'uploads/articles/' . $article->getImageUrl() : 'assets/default-image.jpg';
    
                    $articles[] = [
                        'id' => $article->getId(),
                        'name' => $article->getName(),
                        'price' => $article->getPrice(),
                        'imageUrl' => $imageUrl,
                        'quantity' => $quantity,
                        'subtotal' => $article->getPrice() * $quantity
                    ];
                    
                    $total += $article->getPrice() * $quantity;
                }
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
    public function addToCart(int $id, CartService $cartService, ArticleRepository $articleRepository): RedirectResponse
    {
        $article = $articleRepository->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé.');
        }

        $cartService->addToCart($id, 1);

        return $this->redirectToRoute('app_cart_index');
    }
}