<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Performance;
use App\Entity\PerformanceSearch;
use App\Form\PerformanceSearchType;
use App\Repository\ArtistRepository;
use App\Repository\PerformanceRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    const NB_PERFORMANCES = 6;

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

    public function showPerformances(Request $request, PerformanceRepository $performanceRepository, PaginatorInterface $paginator)
    {

        $search = new PerformanceSearch();
        $form = $this->createForm(PerformanceSearchType::class, $search);
        $form->handleRequest($request);

        $performances = $paginator->paginate(
            $performanceRepository->findPerformanceSearchQuery($search),
            $request->query->getInt('page', 1),
            self::NB_PERFORMANCES);

        return $this->render('wild_circus/performances.html.twig', [
            'performances' => $performances,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/artists", name="artists")
     * @param ArtistRepository $artistRepository
     * @return Response
     */

    public function showArtists(ArtistRepository $artistRepository)
    {

        $artists = $artistRepository->findAll();

        return $this->render('wild_circus/artists.html.twig', [
            'artists' => $artists,

        ]);
    }

    /**
     * @Route("/performance/show/{id}", name="show_performance", methods={"GET"})
     * @param Performance $performance
     * @return Response
     */
    public function showOnePerformance(Performance $performance)
    {
        return $this->render('wild_circus/show_performance.html.twig', [
            'performance' => $performance,
            'id' => $performance->getId()
        ]);
    }

    /**
     * @Route("/artist/show/{id}", name="show_artist", methods={"GET"})
     * @param Artist $artist
     * @return Response
     */
    public function showOneArtist(Artist $artist)
    {
        return $this->render('wild_circus/show_artist.html.twig', [
            'artist' => $artist,
            'id' => $artist->getId()
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
