<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\GroupMembers;

class ApplicationController extends AbstractController {

    /**
     * @Route("/group/{groupName}/applicationSubscriptions/{pageNumber}", name="application_subscription")
     */
    public function applicationSubscription() {
        $publicGroupName = "Public";
        $privateGroupName = "Private";
        return $this->render('dashboard/index.html.twig',[
            'publicGroupName' => $publicGroupName,
            'privateGroupName' => $privateGroupName,
        ]);
    }

}
