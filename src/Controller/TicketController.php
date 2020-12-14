<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Ticket;
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
        return $this->render('ticket/index.html.twig', [
            'controller_name' => 'TicketController',
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
}
