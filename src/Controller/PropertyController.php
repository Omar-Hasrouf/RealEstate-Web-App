<?php /** @noinspection SpellCheckingInspection */

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Notification\ContactNotification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormView;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
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
        $search = new PropertySearch();
        $form   = $this->createForm(PropertySearchType::class, $search);
        $form->handleRequest($request);

        $properties = $paginator->paginate(
            $this->repository->findAllNotSoldQuery($search),
            $request->query->getInt('page', 1),
            12
        );
        return $this->render('property/index.html.twig',[
            'current_menu' => 'properties',
            'properties'   => $properties,
            'form'         => $form->createView()
        ]);
    }

    /**
     * @Route("/biens/{slug}-{id}", name="property.show", requirements={ "slug": "[a-z0-9\-]*" })
     * @param int $id
     * @param string $slug
     * @param Request $request
     * @param ContactNotification $notification
     * @return Response
     */
    public function show($id, $slug, Request $request, ContactNotification $notification){
        $property = $this->getDoctrine()->getRepository(Property::class)->find($id);

        if ($property->getSlug() !== $slug) {
            return $this->redirectToRoute('property.show', ['id' => $property->getId(), 'slug' => $property->getSlug()],
                301);
        }

        $contact = new Contact();
        $contact->setProperty($property);
        $form    = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $notification->notify($contact);
            $this->addFlash('success','Votre email a bien été envoyé');
            return $this->redirectToRoute('property.show', [
                'id'   => $property->getId(),
                'slug' => $property->getSlug()
            ]);
        }

        return $this->render('property/show.html.twig', ['property' => $property, 'form' => $form->createView()]);
    }

}