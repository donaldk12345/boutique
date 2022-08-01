<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Produit;
use App\Form\CategoryType;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ProduitRepository $produitRepository): Response
    {
       
        return $this->render('home/index.html.twig', [
            'produits'=>$produitRepository->findByMin()
        
        ]);
    }

    /**
     * 
     * @Route("/new-produits", name="new-produits")
     * @Security("is_granted('ROLE_ADMIN')") 
     */
    public function insertProduit(Request $request, EntityManagerInterface $manager,SluggerInterface $slugger): Response
    {

        $produit =new Produit();
        $form=$this->CreateForm(ProduitType::class ,$produit);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $file = $form->get('imageName')->getData();
            if($file){
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
                // Move the file to the directory where brachures are stored
                try{
                    $file->move(
                        $this->getParameter('images_directory'), // in Servis.yaml defined folder for
                        $fileName
                    );
                } catch (FileException $e){
                    // ... handle exception f something happens during file upload
                }
                $produit->setImageName($fileName);
            }
            $produit->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($produit);
            //dd($produit);
            $manager->flush();
            return $this->redirectToRoute('produit');
        }
        

        return $this->render('home/produit.html.twig', [
            'form' =>$form->createView()
        ]);
           
    }

     /**
     * 
     * @Route("/category", name="category")
     * @Security("is_granted('ROLE_ADMIN')") 
     * 
     */
    public function categorieSave(Request $request, EntityManagerInterface $manager){
        $category = new Category();
        $form=$this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($category);
            $manager->flush();

        }
        return $this->render('home/category.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
