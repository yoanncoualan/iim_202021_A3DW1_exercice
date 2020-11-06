<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Service\Utile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produit", name="produit")
     */
    public function index(Request $request, Utile $utile): Response
    {
        $em = $this->getDoctrine()->getManager();

        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $slug = $utile->generateUniqueSlug($produit->getNom(), 'Produit');
            $produit->setSlug($slug);

            $em->persist($produit);
            $em->flush();

            $this->addFlash('success', 'Produit ajouté');
        }
    
        $produits = $em->getRepository(Produit::class)->findAll();

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'ajout' => $form->createView()
        ]);
    }

    /**
     * @Route("/produit/{slug}", name="show_produit")
     */
    public function show(Produit $produit = null){
        if($produit == null){
            $this->addFlash('error', 'Produit introuvable');
            return $this->redirectToRoute('produit');
        }

        return $this->render('produit/show.html.twig', [
            'produit' => $produit
        ]);
    }
}
