<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Form\MatiereType;
use App\Form\AddIntervenantType;


use App\Repository\MatiereRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/matiere")
 */
class MatiereController extends AbstractController
{
    /**
     * @Route("/", name="matiere_index", methods={"GET"})
     */
    public function index(MatiereRepository $matiereRepository): Response
    {
        return $this->render('matiere/index.html.twig', [
            'matieres' => $matiereRepository->findAll(),
        ]);
    }
    /**
     * @Route("/matiereList", name="matiere_list_sansIntervenant", methods={"GET"})
     */
    public function listMatiere(MatiereRepository $matiereRepository): Response
    {
         return $this->render('matiere/listMatiere.html.twig', [
            'matieres' => $this->getDoctrine()->getRepository('app\Entity\Matiere')->findnotIntervenant()
        ]);
    }

    /**
     * @Route("/new", name="matiere_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $matiere = new Matiere();
        $form = $this->createForm(MatiereType::class, $matiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($matiere);
            $entityManager->flush();

            return $this->redirectToRoute('matiere_index');
        }

        return $this->render('matiere/new.html.twig', [
            'matiere' => $matiere,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="matiere_show", methods={"GET"})
     */
    public function show(Matiere $matiere): Response
    {
        $notes = array();
        foreach ($matiere->getProjets() as $project) {
            if ($project->getNote() !== null) {
                array_push($notes, $project->getNote());
            }
        }
        if (count($notes)) {
            $average = array_sum($notes) / count($notes);
        } else {
            $average = "None";
        }
         return $this->render('matiere/show.html.twig', [
            'matiere' => $matiere,
            'average' => $average       
            ]);
    }
 /**
     * @Route("/{id}/newMatiere", name="intervenant_matiere", methods={"GET","POST"})
     */
    public function newMatiere(Request $request,Matiere $matiere) 
    {
        $form = $this->createForm(AddIntervenantType::class, $matiere);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($matiere);
            $entityManager->flush();
            return $this->redirectToRoute('matiere_index');
        }
        return $this->render('matiere/addIntervenant.html.twig', [
            'form' => $form->createView()
        ]);
        
    }
    /**
     * @Route("/{id}/edit", name="matiere_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Matiere $matiere): Response
    {
        $option['intervenant']=$matiere->getIntervenant();

        $form = $this->createForm(MatiereType::class, $matiere,$option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('matiere_index');
        }

        return $this->render('matiere/edit.html.twig', [
            'matiere' => $matiere,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="matiere_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Matiere $matiere): Response
    {
        if ($this->isCsrfTokenValid('delete'.$matiere->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($matiere);
            $entityManager->flush();
        }

        return $this->redirectToRoute('matiere_index');
    }

    
}
