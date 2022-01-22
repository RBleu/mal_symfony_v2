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
    protected function getPrevNextSeasons($season)
    {
        $seasons = ['Winter', 'Spring', 'Summer', 'Fall'];
    
        $split = explode(' ', $season);
    
        $iCurrent = array_search($split[0], $seasons);
        $currentYear = (int)$split[1];
    
        $iPrev = $iCurrent - 1;
        $prevYear = ($iPrev < 0)? $currentYear - 1 : $currentYear;
        $prevSeason = $seasons[(4 + $iPrev)%4].' '.$prevYear;
    
        $iNext = $iCurrent + 1;
        $nextYear = ($iNext > 3)? $currentYear + 1 : $currentYear;
        $nextSeason = $seasons[$iNext%4].' '.$nextYear;
    
        $iNextNext = $iNext + 1;
        $nextNextYear = ($iNextNext > 3)? $currentYear + 1 : $currentYear;
        $nextNextSeason = $seasons[$iNextNext%4].' '.$nextNextYear;
    
        return [$prevSeason, $season, $nextSeason, $nextNextSeason];
    }    

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
    public function profile(string $username, ManagerRegistry $doctrine) : Response
    {
        $userRepos = $doctrine->getRepository(User::class);

        if($userRepos->exists($username))
        {
            $profile = $userRepos->getProfileByUsername($username)[0];
            $stats = $userRepos->getProfileStats($username);
            $totalAnimes = array_sum($stats);
            $history = $userRepos->getProfileHistory($profile['id']);
            $totalEpisodes = $userRepos->getProfileTotalEpisodes($profile['id'])[0]['total_episodes'];

            $lists = $doctrine->getRepository(ListType::class)->findAll();

            $statsGraphWidth = 380;
            $statsGraphDivs = '';
            $statsGraphDetail = '';

            foreach($lists as $value)
            {
                $list = $value->getName();
                $listEntries = $stats[$list];
                $className = $value->getListKey();

                $statsGraphDivWidth = ($totalAnimes == 0)? 0 : round(($listEntries/$totalAnimes) * $statsGraphWidth);

                $statsGraphDivs .= '<div class=\''.$className.'\' style=\'width: '.$statsGraphDivWidth.'px\'></div>';
                $statsGraphDetail .= '<div><div class=\'circle '.$className.'\'></div><a href=\'#\' class=\'link\'>'.$list.'</a><div class=\'value\'>'.$listEntries.'</div></div>';
            }

            return $this->render('mal/profile.html.twig', [
                'controller_name' => 'MalController',
                'profile' => $profile,
                'stats' => $stats,
                'total_animes' => $totalAnimes,
                'history' => $history,
                'total_episodes' => $totalEpisodes,
                'stats_graph_divs' => $statsGraphDivs,
                'stats_graph_detail' => $statsGraphDetail,
            ]);
        }
        else
        {
            // DO SOMETHING
        }
    }

    #[Route('/season/{season}', name: 'season')]
    public function season(string $season, ManagerRegistry $doctrine) : Response
    {
        $currentSeason = 'Summer 2021';

        if(!preg_match('/(Winter|Spring|Summer|Fall) [0-9]{4}/', $season))
        {
            return $this->redirectToRoute('season', [
                'season' => $currentSeason,
            ]);
        }

        $animeRepos = $doctrine->getRepository(Anime::class);

        $animes = $animeRepos->getAnimesBySeason($season);
        $seasons = $this->getPrevNextSeasons($season);

        return $this->render('mal/season.html.twig', [
            'controller_name' => 'MalController',
            'animes' => $animes,
            'seasons' => $seasons,
            'current_season' => $currentSeason,
            'season' => $season,
        ]);
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
            $userList = $userListRepos->getListOf($user->getUsername(), $anime->getId());

            if($userList)
            {
                $userList = $userList[0];
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
    public function genre(Genre $genre) : Response
    {
        $title = $genre->getName().' - MAL';
        $headerTitle = $genre->getName().' Anime';

        $animes = $genre->getAnimes();

        return $this->render('mal/searchBase.html.twig', [
            'controller_name' => 'MalController',
            'title' => $title,
            'header_title' => $headerTitle,
            'animes' => $animes,
        ]);
    }

    #[Route('/jump', name: 'jump')]
    public function jump(Request $request) : Response
    {
        $season = ucfirst($request->get('season-select'));
        $year = $request->get('year');

        return $this->redirectToRoute('season', [
            'season' => $season.' '.$year,
        ]);
    }
}
