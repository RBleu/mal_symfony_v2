<?php

namespace App\Controller;

use App\Entity\Anime;
use App\Entity\Genre;
use App\Entity\ListType;
use App\Entity\User;
use App\Entity\UserList;
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
    public function anime(Anime $anime, ManagerRegistry $doctrine) : Response
    {
        $themes = [];

        foreach($anime->getThemes() as $theme)
        {
            $themes[$theme->getType()][] = $theme->getName();
        }

        $lists = $doctrine->getRepository(ListType::class)->findAll();

        $isAlreadyAdd = false;
        $selectedKey = 'plan-to-watch';
        $progressEpisodes = 0;
        $score = 11;

        if($user = $this->getUser())
        {
            $userListRepos = $doctrine->getRepository(UserList::class);
            $userList = $userListRepos->getListOf($user->getUsername(), $anime->getId())[0];

            if($userList)
            {
                $isAlreadyAdd = true;
                $selectedKey = $userList['lt_list_key'];
                $progressEpisodes = $userList['ul_progress_episodes'];
                $score = $userList['ul_score'];
            }
        }

        return $this->render('mal/anime.html.twig', [
            'controller_name' => 'MalController',
            'anime' => $anime,
            'themes' => $themes,
            'lists' => $lists,
            'is_already_add' => $isAlreadyAdd,
            'selected_key' => $selectedKey,
            'progress_episodes' => $progressEpisodes,
            'score' => $score,
        ]);
    }

    #[Route('/genre/{id}', name: 'genre')]
    public function genre(Genre $genre)
    {

    }
}
