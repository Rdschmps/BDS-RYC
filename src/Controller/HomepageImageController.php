<?php

namespace App\Controller;

use App\Entity\HomepageImage;
use App\Form\HomepageImageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/homepage')]
class HomepageImageController extends AbstractController
{
    #[Route('/edit', name: 'admin_homepage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // On récupère les images existantes ou on en crée une nouvelle entrée si elle n'existe pas encore
        $homepageImages = $entityManager->getRepository(HomepageImage::class)->find(1);

        if (!$homepageImages) {
            $homepageImages = new HomepageImage();
        }

        $form = $this->createForm(HomepageImageType::class, $homepageImages);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $imageFile1 */
            $imageFile1 = $form->get('image1')->getData();
            if ($imageFile1) {
                $newFilename1 = uniqid() . '.' . $imageFile1->guessExtension();
                try {
                    $imageFile1->move($this->getParameter('homepage_images_directory'), $newFilename1);
                    $homepageImages->setImage1($newFilename1);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l’upload de l’image 1.');
                }
            }

            /** @var UploadedFile $imageFile2 */
            $imageFile2 = $form->get('image2')->getData();
            if ($imageFile2) {
                $newFilename2 = uniqid() . '.' . $imageFile2->guessExtension();
                try {
                    $imageFile2->move($this->getParameter('homepage_images_directory'), $newFilename2);
                    $homepageImages->setImage2($newFilename2);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l’upload de l’image 2.');
                }
            }

            $entityManager->persist($homepageImages);
            $entityManager->flush();

            $this->addFlash('success', 'Les images ont été mises à jour.');
            return $this->redirectToRoute('admin_homepage_edit');
        }

        return $this->render('admin/homepage_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
