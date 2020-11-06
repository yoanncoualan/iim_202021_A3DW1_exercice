<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Service\Utile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

class CategorieController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, Utile $utile): Response
    {
        $em = $this->getDoctrine()->getManager();

        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $slug = $utile->generateUniqueSlug($categorie->getNom(), 'Categorie');
            $categorie->setSlug($slug);

            $em->persist($categorie);
            $em->flush();

            $this->addFlash('success', 'Catégorie ajoutée');
        }

        $categories = $em->getRepository(Categorie::class)->findAll();

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
            'ajout' => $form->createView()
        ]);
    }

    /**
     * @Route("/categorie/{slug}", name="show_categorie")
     */
    public function show(Categorie $categorie = null){
        if($categorie == null){
            $this->addFlash('error', 'Catégorie introuvable');
            return $this->redirectToRoute('home');
        }

        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie
        ]);
    }
}
