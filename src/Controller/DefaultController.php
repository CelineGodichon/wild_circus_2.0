<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        return $this->render('homepage/index.html.twig', [
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
