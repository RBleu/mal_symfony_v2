<?php

namespace App\Controller;

use App\Entity\Anime;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MalController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $animeRepos = $doctrine->getRepository(Anime::class);

        $currentSeason = 'Summer 2021';
        $currentSeasonAnimes = $animeRepos->getAnimesBySeason($currentSeason);

        $topAnimes = [];
        $topAnimes['Top Airing Anime']   = $animeRepos->getTopAnimes('a.score', 5, 'a.airing = 1');
        $topAnimes['Top Upcoming Anime'] = $animeRepos->getTopAnimes('a.members', 5, 'a.status = \'Not yet aired\'');
        $topAnimes['Most Popular Anime'] = $animeRepos->getTopAnimes('a.members', 10);

        $stats = null;

        if($user = $this->getUser())
        {
            $userRepos = $doctrine->getRepository(User::class);
            $stats = $userRepos->getProfileStats($user->getUsername());
        }

        return $this->render('mal/index.html.twig', [
            'controller_name' => 'MalController',
            'current_season' => $currentSeason,
            'current_season_animes' => $currentSeasonAnimes,
            'top_animes' => $topAnimes,
            'stats' => $stats,
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

    #[Route('/season/{season}', name: 'season')]
    public function season($season)
    {

    }

    #[Route('/anime/{id}', name: 'anime')]
    public function anime($id)
    {
        
    }
}
