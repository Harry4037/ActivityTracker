<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GroupController extends AbstractController
{
    /**
     * @Route("/group", name="group")
     */
    public function index()
    {
        return $this->render('group/index.html.twig', [
            'controller_name' => 'GroupController',
        ]);
    }
    
    /**
     * @Route("/group/create", name="group_create")
     */
    public function create()
    {
        return $this->render('group/create.html.twig', [
            'controller_name' => 'GroupController',
        ]);
    }
}
