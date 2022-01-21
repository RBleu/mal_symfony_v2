<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MalController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('mal/index.html.twig', [
            'controller_name' => 'MalController',
        ]);
    }

    #[Route('/animelist/{username}', name: 'animelist')]
    public function animelist(string $username) : Response
    {
        return new Response('Hello '.$username);
    }

    #[Route('/search', name: "search")]
    public function search(Request $request)
    {
        $searchTitle = $request->query->get('q');
        return new Response('Searched title : '.$searchTitle);
    }

    #[Route('/profile/{username}', name: 'profile')]
    public function profile(string $username)
    {
        return null;
    }
}
