<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Performance;
use App\Entity\PerformanceSearch;
use App\Entity\User;
use App\Form\PerformanceSearchType;
use App\Repository\ArtistRepository;
use App\Repository\PerformanceRepository;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    const NB_PERFORMANCES = 6;

    /**
     * @Route("/", name="homepage")
     * @param User $user
     * @param PerformanceRepository $performanceRepository
     * @param ArtistRepository $artistRepository
     * @return Response
     */
    public function index(PerformanceRepository $performanceRepository, ArtistRepository $artistRepository)
    {
        $user = $this->getUser();
        $performances = $performanceRepository->findAll();
        shuffle($performances);
        $performances = array_slice($performances, 0, 3);

        $artists = $artistRepository->findAll();
        shuffle($artists);
        $artists = array_slice($artists, 0, 3);

        return $this->render('homepage/index.html.twig', [
            'artists' => $artists,
            'performances' => $performances,
            'user' => $user
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

    /**
     * @Route("/{performance}/win", name="win")
     * @param Performance $performance
     * @param MailerInterface $mailer
     * @return Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function winTicket(Performance $performance, MailerInterface $mailer)
    {
        $email = (new Email())
            ->from($this->getParameter('mailer_from'))
            ->to($this->getParameter('mailer_from'))
            ->subject('WILD FREAK CIRCUS : Votre ticket pour ' . $performance->getName() . ' in ' . $performance->getCity()->getName())
            ->html($this->renderView('/email/new_ticket.html.twig', [
                'performance' => $performance
            ]));

        $mailer->send($email);

        return $this->render('wild_circus/ticket_win.html.twig', [
            'performance' => $performance,
        ]);
    }

    /**
     * @Route("/{user}/profile", name="profile")
     * @param User $user
     * @return Response
     */

    public function profile(User $user)
    {
        $performances = $user->getPerformances();

        return $this->render('wild_circus/profile.html.twig', [
            'user' => $user,
            'performances' => $performances
        ]);
    }
}
