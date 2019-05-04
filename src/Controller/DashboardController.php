<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\GroupMembers;

class DashboardController extends AbstractController {

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index() {

        $groups = $this->getDoctrine()->getRepository(GroupMembers::class)->getListOfGroupsUserIsMemberOf($this->getUser());

        return $this->render('dashboard/index.html.twig', [
                    'controller_name' => 'DashboardController',
                    'groups' => $groups
        ]);
    }

}
