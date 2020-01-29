<?php

namespace App\Controller;

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
    public function index(PerformanceRepository $performanceRepository)
    {
        $performances = $performanceRepository->findAll();

        return $this->render('homepage/index.html.twig', [
            'performances' => $performances,
        ]);
    }


    /**
     * @Route("/admin", name="admin")
     */
    public function boardtable()
    {
        return $this->render('admin/boardtable.html.twig', [
        ]);
    }
}
