<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Form\Produit1Type;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProduitController extends AbstractController
{

    /**
     *@Route("/produit", name="produit") 
     *@Security("is_granted('ROLE_ADMIN')") 
     */
    public function index(ProduitRepository $produitRepository, PaginatorInterface $paginator, Request $request, ManagerRegistry $doctrine): Response
    {
        $donnees = $doctrine->getRepository(Produit::class)->findAll();
        $produits = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('produit/index.html.twig', [
            'produits' => $produits
        ]);
    }


    /**
     *@Route("/activer/{id}", name="activer",methods={"GET","POST"})
     */
    public function activer(ProduitRepository $produitRepository, EntityManagerInterface $em, $id)
    {
        $produit = $produitRepository->findOneBy(['id' => $id]);
        $produit->setActive(($produit->getActive()) ? false : true);
        $em->persist($produit);
        $em->flush($produit);

        return new Response("true");
    }

    /**
     *@Route("/promo/{id}", name="promo",methods={"GET","POST"})
     */
    public function enPromo(ProduitRepository $produitRepository, EntityManagerInterface $em, $id)
    {
        $produit = $produitRepository->findOneBy(['id' => $id]);
        $produit->setPromo(($produit->getPromo()) ? false : true);
        $em->persist($produit);
        $em->flush($produit);

        return new Response("true");
    }



    /**
     * @Route("/{id}/edit", name="app_produit_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN')") 
     */
    public function edit($id, Request $request, ProduitRepository $produitRepository, EntityManagerInterface $entityManagerInterface, SluggerInterface $slugger): Response
    {
        $produit = $produitRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            /** @var file $file*/
            $file = $form->get('imageName')->getData();
            if ($file) {

                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                }
                $produit->setImageName($fileName);
            }
            $entityManagerInterface->flush();

            return $this->redirectToRoute('produit');
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }



    /**
     * @Route("/supprime/produit/{id}", name="produit_delete_data", methods={"DELETE"})
     * @Security("is_granted('ROLE_ADMIN')") 
     * 
     */
    public function deleteProduit($id, ProduitRepository $produitRepository, Request $request, EntityManagerInterface $entityManagerInterface)
    {
        $produit = $produitRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        // On vérifie si le token est valide
        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $data['_token'])) {
            // On récupère le nom de l'image
            $nom = $produit->getImageName();
            // On supprime le fichier
            unlink($this->getParameter('images_directory') . '/' . $nom);

            // On supprime l'entrée de la base

            $entityManagerInterface->remove($produit);

            $entityManagerInterface->flush();

            // On répond en json
            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }
}
