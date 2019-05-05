<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Groups;

class SearchController extends AbstractController {

    /**
     * @Route("/search", name="search")
     */
    public function search(Request $request) {
        if ($request->isMethod("GET")) {
            $category = $request->get('category');
            $numResults = 0;

            if ($category == "all" || $category == "groups") {
                //if search keyword is private group return private group else this
                $groups = $this->getDoctrine()
                        ->getRepository(Groups::class)
                        ->createQueryBuilder('G')
                        ->where('G.groupName LIKE :name')
                        ->orWhere('G.description LIKE :description')
                        ->setParameter('name', '%' . $request->get('query') . '%')
                        ->setParameter('description', '%' . $request->get('query') . '%')
                        ->getQuery()
                        ->getResult();
                //filter personal group
//                dd($groups);
            } else {
                $groups = [];
//                $this->numGroups = 0;
            }

//            if ($category == "all" || $category == "applications") {
//                $this->applications = ApplicationPeer::search($this->getRequestParameter('query'));
//                $this->numApplications = count($this->applications);
//                $numResults += $this->numApplications;
//            } else {
//                $this->numApplications = 0;
//            }
//            $this->numResults = $numResults;

            return $this->render('search/search.html.twig', [
                        'groups' => $groups
            ]);
        }
    }

}
