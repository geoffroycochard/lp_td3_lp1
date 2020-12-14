<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Ticket;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @param Ticket $ticket
     * @return Response
     *
     * @Route("/{id}/view", name="ticket.view")
     */
    public function view(Ticket $ticket): Response
    {
        return $this->render('ticket/view.html.twig', [
            'ticket' => $ticket
        ]);
    }

    /**
     * @return Response
     *
     * @Route("/create-ticket-category")
     */
    public function createTicketWithCategory(): Response
    {
        $category = (new Category())->setTitle('Titre de la categorie');

        $ticket = (new Ticket())
            ->setTitle('titre de mon premier ticket')
            ->setDescription('description')
            ->setCategory($category)
        ;

        $em = $this->getDoctrine()->getManager();
        $em->persist($ticket);
        $em->flush();

        dump('coucou');
        die();
        return new Response('created');

    }

    /**
     * @return Response
     *
     * @Route("/create-users-tickets")
     */
    public function createUserAndAssignToTickets(): Response
    {
        // User creation
        $users = [];
        $em = $this->getDoctrine()->getManager();
        foreach (['geoffroycochard', 'bot'] as $username) {
            $users[] = $user = (new User())->setUsername($username);
            $em->persist($user);
        }
        $em->flush();

        // Assign to tickets
        $tickets = $this->getDoctrine()->getRepository(Ticket::class)
            ->findAll();
        /** @var Ticket $ticket */
        foreach ($tickets as $ticket) {
            foreach ($users as $user) {
                $ticket->addUser($user);
            }
        }
        $em->flush();

        return new Response('created');
    }
}
