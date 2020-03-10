<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Entity\Option;
use App\Form\PropertyType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;



class AdminPropertyController extends AbstractController{
    
    /**
     * @var PropertyRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(PropertyRepository $repository, EntityManagerInterface $em){
        $this->repository = $repository;
        $this->em         = $em;
    }
    
    /**
     * @Route("/admin/", name="admin.property.index")
     * @return \Symfony\Component\HttFoundation\Response
     */
    public function index(): Response
    {
        $properties = $this->repository->findAll();
        return $this->render('admin/property/index.html.twig', compact('properties'));
    }

    /**
     * @Route("/admin/property/create", name="admin.property.add")
     */
    public function add(Request $request)
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($property);
            $this->em->flush();
            $this->addFlash('success', 'Bien créé avec succès!');
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/add.html.twig', [
            'property' => $property,
            'form'     => $form->createView()
        ]); 
    }


    /**
     * @Route("/admin/property/{id}", name="admin.property.edit", methods="GET|POST")
     * @param Property $property
     * @param Request $request
     * @return \Symfony\Component\HttFoundation\Response
     */
    public function edit(Property $property, Request $request)
    {
        $form = $this->createForm(PropertyType::class, $property); // soit un tableau, soit une entité
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //$this->em->persist($property);
            $this->em->flush();
            
            $this->addFlash('success', 'Bien modifié avec succès!');
            return $this->redirectToRoute('admin.property.index');
        }
        return $this->render('admin/property/edit.html.twig', [
            'property' => $property,
            'form'     => $form->createView()
        ]); 
    }

    /**
     * @Route("/admin/property/{id}", name="admin.property.delete", methods="DELETE")
     * @param Property $property
     * @return \Symfony\Component\HttFoundation\Response
     */
    public function delete(Property $property, Request $request){
        if($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token'))){
            $this->em->remove($property);
            $this->em->flush();
            $this->addFlash('success', 'Bien supprimé avec succès!');
            return new Response('Suppression');
        }
        return $this->redirectToRoute('admin.property.index');
    }
}