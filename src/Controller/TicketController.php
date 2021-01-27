<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Ticket;
use App\Entity\User;
use App\Form\TicketType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TicketController extends AbstractController
{
    /**
     * @Route("/ticket", name="ticket")
     */
    public function index(): Response
    {
        $tickets = $this->getDoctrine()->getRepository(Ticket::class)
            ->findAll();

        return $this->render('ticket/index.html.twig', [
            'controller_name' => 'TicketController',
            'tickets' => $tickets
        ]);
    }

    /**
     * @Route("/ticket/{id}/view", name="ticket.view")
     */
    public function view(Ticket $ticket): Response
    {
        return $this->render('ticket/view.html.twig', [
            'ticket' => $ticket
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/ticket/new")
     */
    public function new(Request $request): Response
    {
        $form = $this->createForm(TicketType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Ticket $ticket */
            $ticket = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($ticket);
            $em->flush();
            return $this->redirectToRoute('ticket.view', [
                'id' => $ticket->getId()
            ]);
        }


        return $this->render('ticket/edit.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/ticket/{id}/edit")
     */
    public function edit(Ticket $ticket, Request $request): Response
    {
        $form = $this->createForm(TicketType::class, $ticket);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ticket = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($ticket);
            $em->flush();
            return $this->redirectToRoute('ticket.view', [
                'id' => $ticket->getId()
            ]);
        }


        return $this->render('ticket/edit.html.twig', [
            'form' => $form->createView()
        ]);

    }









//
//    /**
//     * @Route("/ticket/{id}/edit", name="ticket.edit")
//     */
//    public function edit(Ticket $ticket, Request $request): Response
//    {
//        $form = $this->createForm(TicketType::class, $ticket);
//
//        $form->handleRequest($request);
//
//        if($form->isSubmitted() && $form->isValid()) {
//            $ticket = $form->getData();
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($ticket);
//            $em->flush();
//            return $this->redirectToRoute('ticket.view', ['id' => $ticket->getId()]);
//        }
//
//        return $this->render('ticket/edit.html.twig', [
//            'form' => $form->createView()
//        ]);
//    }
//
//    /**
//     * @Route("/ticket/new")
//     */
//    public function new(Request $request)
//    {
//        $form = $this->createForm(TicketType::class);
//
//        $form->handleRequest($request);
//
//        if($form->isSubmitted() && $form->isValid()) {
//            dump($form->getData());
//            $ticket = $form->getData();
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($ticket);
//            $em->flush();
//            return $this->redirectToRoute('ticket.view', ['id' => $ticket->getId()]);
//        }
//
//        return $this->render('ticket/edit.html.twig', [
//            'form' => $form->createView()
//        ]);
//
//
//    }

    /**
     * @return Response
     *
     * @Route("/create-tickets-with-category")
     */
    public function createTicketWithCategory(): Response
    {
        $category = (new Category())->setTitle('category');

        $ticket = (new Ticket())
            ->setTitle('Ticket title')
            ->setDescription('description')
            ->setCategory($category)
        ;

        $em = $this->getDoctrine()->getManager();
        $em->persist($ticket);
        $em->flush();

        return new Response('ticket created');

    }


    /**
     * @return Response
     *
     * @Route("/create-user-tickets")
     */
    public function createNewUsersAndAssignToTickets(): Response
    {
        // Users create
        $em = $this->getDoctrine()->getManager();
        $users = [];
        foreach (['geoffroycohard', 'bot'] as $username) {
            $users[] = $user = (new User())->setUsername($username);
            $em->persist($user);
        }
        $em->flush();

        // Assign users to existing tickets

        $tickets = $this->getDoctrine()->getRepository(Ticket::class)->findAll();
        /** @var Ticket $ticket */
        foreach ($tickets as $ticket) {
            foreach ($users as $user) {
                $ticket->addUser($user);
                dump($ticket);
            }
        }
        $em->flush();

        return new Response('crated');

    }

    /**
     * @return Response
     *
     * @Route("/validate")
     */
    public function validate(ValidatorInterface $validator): Response
    {
        $user = (new User());

        $violations = $validator->validate($user);

        dump($violations);

        die();


    }


    /**
     * @param FormFactoryInterface $factory
     * @return Response
     *
     * @Route("/contact")
     */
    public function contact(FormFactoryInterface $factory, Request $request)
    {
        $builder = $factory->createBuilder();
        $builder
            ->add('name', TextType::class)
            ->add('message', TextareaType::class)
        ;

        $form = $builder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            dump($form->getData()); die();
        }

//        dump($form->isSubmitted());



        //dump($form->isValid());
//        die();
        return $this->render('ticket/contact.html.twig', [
            'form' => $form->createView()
        ]);

    }


}
