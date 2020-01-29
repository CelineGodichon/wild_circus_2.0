<?php

namespace App\Controller;

use App\Repository\ArtistRepository;
use App\Repository\PerformanceRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    const NB_PERFORMANCES = 4;
    /**
     * @Route("/", name="homepage")
     * @param PerformanceRepository $performanceRepository
     * @return Response
     */
    public function index(PerformanceRepository $performanceRepository, ArtistRepository $artistRepository)
    {
        $performances = $performanceRepository->findAll();
        shuffle($performances);
        $performances = array_slice($performances, 0, 4);

        $artists = $artistRepository->findAll();
        shuffle($artists);
        $artists = array_slice($artists, 0, 4);

        return $this->render('homepage/index.html.twig', [
            'artists' => $artists,
            'performances' => $performances,
        ]);
    }

    /**
     * @Route("/performances", name="performances")
     * @param Request $request
     * @param PerformanceRepository $performanceRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */

    public function showPerformances(Request $request, PerformanceRepository $performanceRepository, PaginatorInterface $paginator){

        $performances = $paginator->paginate(
            $performanceRepository->findAll(),
        $request->query->getInt('page', 1),
        self::NB_PERFORMANCES);

        return $this->render('wild_circus/performances.html.twig', [
            'performances' => $performances,

        ]);
    }

    /**
     * @Route("/artists", name="artists")
     * @param ArtistRepository $artistRepository
     * @return Response
     */

    public function showArtists(ArtistRepository $artistRepository){

        $artists = $artistRepository->findAll();

        return $this->render('wild_circus/artists.html.twig', [
            'artists' => $artists,

        ]);
    }


    /**
     * @Route("/admin", name="admin_boardtable")
     */
    public function boardtable()
    {
        return $this->render('admin/boardtable.html.twig', [
        ]);
    }
}
