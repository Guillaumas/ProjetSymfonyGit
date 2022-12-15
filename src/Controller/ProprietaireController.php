<?php

namespace App\Controller;

use App\Entity\Proprietaire;
use App\Form\ProprietaireSupprimerType;
use App\Form\ProprietaireType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProprietaireController extends AbstractController
{
    /**
     * @Route("/proprietaire_voir", name="proprietaire")
     */
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        //création du formulaire d'ajout
        $proprietaire=new Proprietaire(); //on crée une catégorie vide
        //on crée un formulaire à partir de la classe CategorieType et de notre objet vide
        $form=$this->createForm(ProprietaireType::class, $proprietaire);

        //Gestion du retour du formulaire
        //on ajoute Request dans les paramètres comme dans le projet précédent
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            //le handleRequest a rempli notre objet $categorie
            //qui n'est plus vide
            //pour sauvegarder, on va récupérer un entityManager de doctrine
            //qui comme son nom l'indique gère les entités
            $em=$doctrine->getManager();
            //on lui dit de la ranger dans la BDD
            $em->persist($proprietaire);

            //générer l'insert
            $em->flush();

        }


        //pour aller chercher les catégories, je vais utiliser un repository
        //pour me servir de doctrine j'ajoute le paramètres $doctrine à la méthode
        $repo = $doctrine->getRepository(Proprietaire::class);
        $proprietaire=$repo->findAll();

        return $this->render('proprietaire/index.html.twig', [
            'proprietaire'=>$proprietaire,
            'formulaire'=>$form->createView()
        ]);
    }

    /**
     * @Route("/proprietaire/modifier/{id}", name="proprietaire_modifier")
     */
    public function modifierProprietaire($id, ManagerRegistry $doctrine, Request $request){
        //récupérer la catégorie dans la BDD
        $proprietaire = $doctrine->getRepository(Proprietaire::class)->find($id);

        //si on n'a rien trouvé -> 404
        if(!$proprietaire){
            throw $this->createNotFoundException("Aucuns proprietaires avec l'id $id");
        }

        //si on arrive là, c'est qu'on a trouvé une catégorie
        //on crée le formulaire avec (il sera rempli avec ses valeurs
        $form=$this->createForm(ProprietaireType::class, $proprietaire);

        //Gestion du retour du formulaire
        //on ajoute Request dans les paramètres comme dans le projet précédent
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            //le handleRequest a rempli notre objet $categorie
            //qui n'est plus vide
            //pour sauvegarder, on va récupérer un entityManager de doctrine
            //qui comme son nom l'indique gère les entités
            $em=$doctrine->getManager();
            //on lui dit de la ranger dans la BDD
            $em->persist($proprietaire);

            //générer l'insert
            $em->flush();

            //retour à l'accueil
            return $this->redirectToRoute("app_home");
        }

        return $this->render("proprietaire/modifier.html.twig",[
            'proprietaire'=>$proprietaire,
            'formulaire'=>$form->createView()
        ]);
    }

    /**
     * @Route("/proprietaire/supprimer/{id}", name="proprietaire_supprimer")
     */
    public function supprimerProprietaire($id, ManagerRegistry $doctrine, Request $request){
        //récupérer la catégorie dans la BDD
        $proprietaire = $doctrine->getRepository(Proprietaire::class)->find($id);

        //si on n'a rien trouvé -> 404
        if(!$proprietaire){
            throw $this->createNotFoundException("Aucuns proprietaire avec l'id $id");
        }

        //si on arrive là, c'est qu'on a trouvé une catégorie
        //on crée le formulaire avec (il sera rempli avec ses valeurs
        $form=$this->createForm(ProprietaireSupprimerType::class, $proprietaire);

        //Gestion du retour du formulaire
        //on ajoute Request dans les paramètres comme dans le projet précédent
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            //le handleRequest a rempli notre objet $categorie
            //qui n'est plus vide
            //pour sauvegarder, on va récupérer un entityManager de doctrine
            //qui comme son nom l'indique gère les entités
            $em=$doctrine->getManager();
            //on lui dit de la supprimer de la BDD
            $em->remove($proprietaire);

            //générer l'insert
            $em->flush();

            //retour à l'accueil
            return $this->redirectToRoute("app_home");
        }

        return $this->render("proprietaire/supprimer.html.twig",[
            'proprietaire'=>$proprietaire,
            'formulaire'=>$form->createView()
        ]);
    }

}
