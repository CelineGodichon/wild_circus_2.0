<?php

namespace App\Controller;

use App\Repository\ArtistRepository;
use App\Repository\PerformanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
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
     * @param PerformanceRepository $performanceRepository
     * @return Response
     */

    public function showPerformances(PerformanceRepository $performanceRepository){

        $performances = $performanceRepository->findAll();

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
