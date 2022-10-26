<?php

namespace App\Controller;

use App\Entity\Pins;
use App\Repository\PinsRepository;
//use Symfony\Component\Form\Extension\Core\Type\{TextType,ButtonType,EmailType,HiddenType,PasswordType,TextareaType,SubmitType,NumberType,DateType,MoneyType,BirthdayType};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{
    #[Route('/',name:"app_home")]
    public function index(PinsRepository $repo): Response
    {
        $pins=$repo->findAll();
        return $this->render('pins/index.html.twig', compact('pins'));
    }
//expressions regulieres->requiements
//Debug:route affiche l'ensemble des routes disponible au niveau de notre App
//Router:Match affiche le path associer a un nom de route que on lui donne en tapent la commande

    #[Route('/pins/{id<\d+>}', name:'app_pins_show', methods: ['GET'])]
    public function show(Pins $pin): Response
    {
        //$pin= $repo->find($id);

       /* if(! $pin){
            throw $this->createNotFoundException('Pin # not found!');
        }*/
        return $this->render('pins/show.html.twig', compact('pin'));
    }
    
    #[Route('/pins/create', name:"app_pins_create", methods:"GET|POST")]
    public function create(Request $request ,EntityManagerInterface $em): Response
    {

        $pin= new Pins;
       // $type= 'Symfony\Component\Form\Extension\Core\Type';

        $form=$this->createFormBuilder($pin)
            ->add('Title',null,['attr'=>['autofocus'=>true,'placeholder'=>'Title pins']])
            ->add('Description',TextareaType::class,['attr'=>['placeholder'=>'Description of pins', 'rows'=>10 ,'cols'=>50]])
            ->getForm()
        ;

        $Monformulaire=$form->CreateView();

        $form->handlerequest($request);

        if ($form->IsSubmitted() && $form->IsValid()){

            $em->persist($pin);
            $em->flush(); 
            return $this->redirectToRoute("app_pins_show",['id'=>$pin->getId()]);
            
        }
        return $this->render('pins/create.html.twig',compact('Monformulaire'));
    }
}
