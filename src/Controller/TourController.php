<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TourController extends AbstractController
{
    /**
     * @Route("/tour/page/{number}", name="tour")
     */
    public function index($number)
    {
        return $this->render('tour/index.html.twig', [
            'controller_name' => 'TourController','number' => $number
        ]);
    }
}
