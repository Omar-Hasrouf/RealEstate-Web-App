<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Property;
use Knp\Component\Pager\PaginatorInterface;

class PropertyController extends AbstractController{
    /**
     * @var PropertyRepository
     */
    private $repository;
    
    /**
     * @var ObjectManager
     */
    private $em;

    // Pq? un page qui permet d'afficher un bien en particulier
    public function __construct(PropertyRepository $repository, EntityManagerInterface $em){
        $this->repository = $repository;
        $this->em         = $em;
    }

    /**
     * @Route("/biens", name="property.index") 
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response{
        $properties = $paginator->paginate(
            $this->repository->findAllNotSoldQuery(),
            $request->query->getInt('page', 1),
            12
        );
        return $this->render('property/index.html.twig',[
            'current_menu' => 'properties',
            'properties'   =>$properties
        ]);
    }
    
    /**
     * @Route("/biens/{slug}-{id}", name="property.show", requirements={ "slug": "[a-z0-9\-]*" }) 
     *  @param Property $property
     *  @return Response
     */
    public function show(Property $property, string $slug):Response{
        if($property->getSlug() !== $slug){
            return $this->redirectToRoute('property.show', [
                'id'   => $property->getId(),
                'slug' => $property->getSlug()
            ], 301);
        }
        return $this->render('property/show.html.twig',[
            'property'     => $property,
            'current_menu' => 'properties'
        ]);
    }
}