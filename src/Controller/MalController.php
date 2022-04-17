<?php

namespace App\Controller;

use App\Entity\Anime;
use App\Entity\Genre;
use App\Entity\ListType;
use App\Entity\Priority;
use App\Entity\User;
use App\Entity\UserList;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
        $currentSeasonAnimes = $animeRepos->findBy(['premiered' => $currentSeason]);

        $topAnimes = [];
        $topAnimes['Top Airing Anime']   = $animeRepos->findBy(['airing' => 1], ['score' => 'DESC'], 5);
        $topAnimes['Top Upcoming Anime'] = $animeRepos->findBy(['status' => 'Not yet aired'], ['members' => 'DESC'], 5);
        $topAnimes['Most Popular Anime'] = $animeRepos->findBy(array(), ['members' => 'DESC'], 10);

        $stats = null;

        if($user = $this->getUser())
        {
            $userRepos = $doctrine->getRepository(User::class);
            $stats = $userRepos->getProfileStats($user->getId());
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
    public function animelist(string $username, ManagerRegistry $doctrine, Request $request) : Response
    {
        $userRepos = $doctrine->getRepository(User::class);

        if($user = $userRepos->findOneBy(['username' => $username]))
        {
            $list = $request->get('list') ?: '5';
            
            $animelist = $doctrine->getRepository(UserList::class)->findBy(['user' => $user->getId(), 'listType' => $list]);
            $lists = $doctrine->getRepository(ListType::class)->findAll();

            return $this->render('mal/animelist.html.twig', [
                'controller_name' => 'MalController',
                'animelist' => $animelist,
                'lists' => $lists,
                'username' => $user->getUsername(),
                'list_id' => $list,
            ]);
        }
        else
        {
            return $this->render('mal/error.html.twig', [
                'controller_name' => 'MalController',
                'error_title' => 'Invalid Username',
                'error_msg' => 'Could not find the user '.$username.'. Please make sure you typed their name in correctly.',
            ]);
        }
    }

    #[Route('/search', name: "search")]
    public function search(Request $request, ManagerRegistry $doctrine) : Response
    {
        $title = $request->get('q');
        $js = $request->get('js');

        $animeRepos = $doctrine->getRepository(Anime::class);

        if($js == 'js')
        {
            $animes = $animeRepos->getAnimesByTitle($title, 10);
            return new JsonResponse($animes);
        }
        else
        {
            $animes = $animeRepos->getAnimesByTitle($title);

            $title = 'Search - MAL';
            $headerTitle = 'Search';
            
            return $this->render('mal/searchBase.html.twig', [
                'controller_name' => 'MalController',
                'title' => $title,
                'header_title' => $headerTitle,
                'animes' => $animes,
            ]);
        }
    }

    #[Route('/profile/{username}', name: 'profile')]
    public function profile(string $username, ManagerRegistry $doctrine) : Response
    {
        $userRepos = $doctrine->getRepository(User::class);

        if($user = $userRepos->findOneBy(['username' => $username]))
        {
            $stats = $userRepos->getProfileStats($user->getId());
            $totalAnimes = array_sum($stats);
            $history = $doctrine->getRepository(UserList::class)->findBy(['user' => $user->getId()], ['modificationDate' => 'DESC'], 3);
            $totalEpisodes = $userRepos->getProfileTotalEpisodes($user->getId());

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
                'profile' => $user,
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
            return $this->render('mal/error.html.twig', [
                'controller_name' => 'MalController',
                'error_title' => 'Invalid Username',
                'error_msg' => 'Could not find the user '.$username.'. Please make sure you typed their name in correctly.',
            ]);
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

        $animes = $animeRepos->findBy(['premiered' => $season]);
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
    public function anime($id, ManagerRegistry $doctrine) : Response
    {
        $animeRepos = $doctrine->getRepository(Anime::class);

        if($anime = $animeRepos->find($id))
        {
            $themes = [];

            foreach($anime->getThemes() as $theme)
            {
                $themes[$theme->getType()][] = $theme->getName();
            }

            $lists = $doctrine->getRepository(ListType::class)->findAll();

            $isAlreadyAdd = false;
            $userList = new UserList();
            $userList->setProgressEpisodes(0);
            $userList->setScore(11);
            $userList->setListType((new ListType())->setListKey('plan-to-watch'));

            if($user = $this->getUser())
            {
                $userListRepos = $doctrine->getRepository(UserList::class);

                if($tmp = $userListRepos->findOneBy(['user' => $user->getId(), 'anime' => $anime->getId()]))
                {
                    $userList = $tmp;
                    $isAlreadyAdd = true;
                }
            }

            return $this->render('mal/anime.html.twig', [
                'controller_name' => 'MalController',
                'anime' => $anime,
                'themes' => $themes,
                'lists' => $lists,
                'is_already_add' => $isAlreadyAdd,
                'user_list' => $userList,
            ]);
        }
        else
        {
            return $this->render('mal/error.html.twig', [
                'controller_name' => 'MalController',
                'error_title' => '404 Not Found',
                'error_msg' => 'This page doesn\'t exist',
            ]);
        }
    }

    #[Route('/genre/{id}', name: 'genre')]
    public function genre($id, ManagerRegistry $doctrine) : Response
    {
        $genreRepos = $doctrine->getRepository(Genre::class);

        if($genre = $genreRepos->find($id))
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
        else
        {
            return $this->render('mal/error.html.twig', [
                'controller_name' => 'MalController',
                'error_title' => '404 Not Found',
                'error_msg' => 'This page doesn\'t exist',
            ]);
        }
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

    #[Route('/add', name: 'add_to_list')]
    public function add(Request $request, ManagerRegistry $doctrine) : Response
    {
        $user = $this->getUser();

        if(!$user)
        {
            return new JsonResponse($this->generateUrl('app_login', [], UrlGeneratorInterface::ABSOLUTE_URL), 400);
        }

        $userList = new UserList();

        $userList->setUser($user);
        $userList->setAnime($doctrine->getRepository(Anime::class)->findOneBy(['id' => $request->get('animeId')]));
        $userList->setListType($doctrine->getRepository(ListType::class)->findOneBy(['id' => $request->get('listId')]));
        $userList->setScore($request->get('score'));
        $userList->setProgressEpisodes($request->get('progressEpisodes'));
        $userList->setModificationDate(new DateTime());
        $userList->setPriority($doctrine->getRepository(Priority::class)->findOneBy(['id' => 2]));

        $entityManager = $doctrine->getManager();

        $entityManager->persist($userList);

        $entityManager->flush();

        return new JsonResponse('Anime '.$request->get('animeId').' has been correctly had into your list');
    }

    #[Route('/update', name: 'update_list')]
    public function update(Request $request, ManagerRegistry $doctrine) : Response
    {
        $user = $this->getUser();

        if(!$user)
        {
            return new JsonResponse($this->generateUrl('app_login', [], UrlGeneratorInterface::ABSOLUTE_URL), 400);
        }

        $userList = $doctrine->getRepository(UserList::class)->findOneBy(['user' => $user->getId(), 'anime' => $request->get('animeId')]);

        $userList->setListType($doctrine->getRepository(ListType::class)->findOneBy(['id' => $request->get('listId')]));
        $userList->setScore($request->get('score'));
        $userList->setProgressEpisodes($request->get('progressEpisodes'));
        $userList->setModificationDate(new DateTime());

        $entityManager = $doctrine->getManager();

        $entityManager->persist($userList);

        $entityManager->flush();

        return new JsonResponse('Anime '.$request->get('animeId').' has been correctly updated in your list');
    }

    #[Route('/delete', name: 'delete_from_list')]
    public function delete(Request $request, ManagerRegistry $doctrine) : Response
    {
        $user = $this->getUser();

        if(!$user)
        {
            return new JsonResponse($this->generateUrl('app_login', [], UrlGeneratorInterface::ABSOLUTE_URL), 400);
        }

        $userList = $doctrine->getRepository(UserList::class)->findOneBy(['user' => $user->getId(), 'anime' => $request->get('animeId')]);

        $entityManager = $doctrine->getManager();

        $entityManager->remove($userList);

        $entityManager->flush();

        return new JsonResponse('Anime '.$request->get('animeId').' has been correctly deleted from your list');
    }
}
