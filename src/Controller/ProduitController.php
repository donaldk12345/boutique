<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Form\Produit1Type;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
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
     * 
     */
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

   

   

     /**
     * @Route("/{id}/edit", name="app_produit_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN')") 
     */
    public function edit(Request $request, Produit $produit,EntityManagerInterface $entityManagerInterface,SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var file $file*/
            $file = $form->get('imageName')->getData();
            if($file){
                
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
                try{
                    $file->move(
                        $this->getParameter('images_directory'), 
                        $fileName
                    );
                } catch (FileException $e){
                   
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

    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $produitRepository->remove($produit, true);
        }

        return $this->redirectToRoute('produit', [], Response::HTTP_SEE_OTHER);
    }

 /**
 * @Route("/supprime/produit/{id}", name="produit_delete_data", methods={"DELETE"})
 * @Security("is_granted('ROLE_ADMIN')") 
 * 
 */
public function deleteProduit(Produit $produit, Request $request,EntityManagerInterface $entityManagerInterface){
    $data = json_decode($request->getContent(), true);

    // On vérifie si le token est valide
    if($this->isCsrfTokenValid('delete'.$produit->getId(), $data['_token'])){
        // On récupère le nom de l'image
        $nom = $produit->getImageName();
        // On supprime le fichier
        unlink($this->getParameter('images_directory').'/'.$nom);

        // On supprime l'entrée de la base
        
        $entityManagerInterface->remove($produit);
       
        $entityManagerInterface->flush();

        // On répond en json
        return new JsonResponse(['success' => 1]);
    }else{
        return new JsonResponse(['error' => 'Token Invalide'], 400);
    }
}
}
