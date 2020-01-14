<?php

namespace App\Controller;

use App\Entity\Intervenant;
use App\Form\IntervenantType;
use App\Repository\IntervenantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/intervenant")
 */
class IntervenantController extends AbstractController
{
    /**
     * @Route("/", name="intervenant_index", methods={"GET"})
     */
    public function index(IntervenantRepository $intervenantRepository): Response
    {
        return $this->render('intervenant/index.html.twig', [
            'intervenants' => $intervenantRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="intervenant_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $intervenant = new Intervenant();
        $form = $this->createForm(IntervenantType::class, $intervenant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($intervenant->getMatieres() as $matiere) {
                $matiere->setIntervenant($intervenant);
            }
            $entityManager->persist($intervenant);
            $entityManager->flush();

            return $this->redirectToRoute('intervenant_index');
        }

        return $this->render('intervenant/new.html.twig', [
            'intervenant' => $intervenant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="intervenant_show", methods={"GET"})
     */
    public function show(Intervenant $intervenant): Response
    {
        return $this->render('intervenant/show.html.twig', [
            'intervenant' => $intervenant,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="intervenant_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Intervenant $intervenant): Response
    {
        $option['matieres']=$intervenant->getMatieres();
        
        foreach ($intervenant->getMatieres() as $matiere) {
            $matiere->setIntervenant(null);
        }
        $form = $this->createForm(IntervenantType::class, $intervenant,$option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
           foreach ($intervenant->getMatieres() as $matiere) {
                $matiere->setIntervenant($intervenant);
            }
           
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('intervenant_index');
        }

        return $this->render('intervenant/edit.html.twig', [
            'intervenant' => $intervenant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="intervenant_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Intervenant $intervenant): Response
    {
        if ($this->isCsrfTokenValid('delete'.$intervenant->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($intervenant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('intervenant_index');
    }
}
