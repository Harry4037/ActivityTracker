<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\GroupMmbers;

class CommonController extends AbstractController {

    public function leftColumn() {

        return $this->render('common/leftColumn.html.twig', [
                    'groups' => $this->getUser()->getGroupMembers()
        ]);
    }
    
    public function rightColumn() {

        return $this->render('common/rightColumn.html.twig', [
                    'groups' => $this->getUser()->getGroupMembers()
        ]);
    }

}
