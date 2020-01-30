<?php


namespace App\Controller;


use App\Entity\Performance;
use App\Entity\Tile;
use App\Repository\PerformanceRepository;
use App\Repository\TileRepository;
use App\Repository\UserRepository;
use App\Services\MapService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    /**
     * @Route("/{performance}/game", name="game")
     * @param UserRepository $userRepository
     * @param SessionInterface $session
     * @return Response
     */
    public function displayGame(UserRepository $userRepository, SessionInterface $session, Performance $performance)
    {

        $em = $this->getDoctrine()->getManager();
        $tiles = $em->getRepository(Tile::class)->findAll();

        foreach ($tiles as $tile) {
            $map[$tile->getCoordX()][$tile->getCoordY()] = $tile;
        }

        $user = $this->getUser();

        return $this->render('game.html.twig', [
            'map' => $map ?? [],
            'user' => $user,
            'performance' => $performance->getId()
        ]);

    }

    /**
     * @param int $direction
     * @param Performance $performance
     * @param EntityManagerInterface $em
     * @param MapService $mapManager
     * @param TileRepository $tileRepository
     * @return RedirectResponse
     * @Route("/{performance}/direction/{direction}", name="move")
     */
    public function moveDirection($direction, Performance $performance, EntityManagerInterface $em, MapService $mapManager, TileRepository $tileRepository)
    {
        $user = $this->getUser();

        $actualX = $user->getCoordX();
        $actualY = $user->getCoordY();

        if ($direction === 'N') {
            $user->setCoordY($actualY - 1);
        } elseif ($direction === 'S') {
            $user->setCoordY($actualY + 1);
        } elseif ($direction === 'W') {
            $user->setCoordX($actualX - 1);
        } elseif ($direction === 'E') {
            $user->setCoordX($actualX + 1);
        } else {
            throw $this->createNotFoundException('Can\'t go this way !! LET\'S MOVE ON !');
        }

        if ($mapManager->tileExists($user->getCoordX(), $user->getCoordY(), $tileRepository)) {
            $em->flush();

            if ($mapManager->checkTicket($user, $tileRepository)) {
                $user = $this->getUser();
                $user->addPerformance($performance);
                $em->flush();

                return $this->redirectToRoute('win', [
                    'performance' => $performance->getId(),
                ]);
            }
        } else {
            $this->addFlash(
                'danger',
                'NOT THIS WAY, hurry up ! They\'re coming to kill you ! '
            );
        }

        return $this->redirectToRoute('game', [
            'performance' => $performance->getId()
        ]);


    }

    /**
     * @Route("/{performance}/start", name="start")
     * @param Performance $performance
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $em
     * @param MapService $mapService
     * @param TileRepository $tileRepository
     * @return RedirectResponse
     */
    public function start(Performance $performance, UserRepository $userRepository, EntityManagerInterface $em, MapService $mapService, TileRepository $tileRepository)
    {
        $user = $this->getUser();
        $user->setCoordX(0);
        $user->setCoordY(0);

        $tiles = $tileRepository->findBy(['hasTicket' => true]);
        foreach ($tiles as $tile) {
            $tile->setHasTicket(false);
        }

        $hideouts = $tileRepository->findBy(['isHideout' => true]);
        foreach ($hideouts as $hideout) {
            $hideout->setIsHideout(false);
        }
        $em->flush();

        $newHideouts = $mapService->getHideouts($tileRepository);
        foreach ($newHideouts as $newHideout) {
            $newHideout->setIsHideout(true);

        }
        $em->flush();

        $mapService->getRandomTile($tileRepository)->setHasTicket(true);
        $em->flush();

        return $this->redirectToRoute('game', [
            'performance' => $performance->getId()]);

    }
}
