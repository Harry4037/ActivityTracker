<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\GroupMembers;
use App\Entity\Groups;
use App\Entity\GroupApplications;

class ApplicationController extends AbstractController {

    /**
     * @Route("/application/{applicationName}", name="application_index")
     */
    public function index() {
        dd("application index");
    }

    /**
     * @Route("/group/{groupName}/applicationSubscriptions/{pageNumber}", name="application_subscription")
     */
    public function applicationSubscription() {
        $publicGroupName = "Public";
        $privateGroupName = "Private";
        return $this->render('dashboard/index.html.twig', [
                    'publicGroupName' => $publicGroupName,
                    'privateGroupName' => $privateGroupName,
        ]);
    }

    /**
     * @Route("/group/{groupName}/applications/list/for/{type}", name="applicationLaunchStep2")
     */
    public function applicationLaunchStep2(Request $request) {
        if ($request->isXmlHttpRequest() && (
                $request->get('type') == "launchApp" ||
                $request->get('type') == "downloadData" ||
                $request->get('type') == "viewEquations")) {
            $group = $this->getDoctrine()
                            ->getRepository(Groups::class)->findOneBy(["groupName" => $request->get('groupName')]);
            $applications = $this->getDoctrine()
                            ->getRepository(GroupApplications::class)->getApplicationSubscriptions($group->getId());
            $groupMember = $this->getDoctrine()
                            ->getRepository(GroupMembers::class)->retrieveGroupMember($this->getUser()->getId(), $group->getId());
            return $this->render('dashboard/applicationLaunch2.html.twig', [
                        'group' => $group,
                        'applications' => $applications,
                        'groupMember' => $groupMember,
            ]);
        }
    }

}
