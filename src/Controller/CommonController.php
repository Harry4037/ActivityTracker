<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\GroupMmbers;
use App\Entity\GroupRequests;
use App\Entity\ApplicationRequests;
use App\Entity\GroupMembershipNotifications;
use App\Entity\GroupApplicationNotifications;
use App\Entity\RecentSimulations;
use App\Entity\ApplicationAdmins;
use App\Entity\IsimulateUpdates;

class CommonController extends AbstractController {

    public function leftColumn() {

        return $this->render('common/leftColumn.html.twig', [
                    'groups' => $this->getUser()->getGroupMembers()
        ]);
    }

    public function rightColumn() {

        // Start Notification section //
        $groupRequests = $this->getDoctrine()
                        ->getRepository(GroupRequests::class)->getRequestsForGroupsThatUserIsAdminOf($this->getUser()->getId());
        $requests = array();
        $lastGroupName = "";
        foreach ($groupRequests as $groupRequest) { /* @var $groupRequest GroupRequest */
            $thisGroupName = $groupRequest['groupName'];
            if ($thisGroupName != $lastGroupName) {
                $requests[$thisGroupName] = 1;
            } else {
                $requests[$thisGroupName] ++;
            }
            $lastGroupName = $thisGroupName;
        }
        $groupRequests = $requests;


        // application requests
        $applicationRequests = $this->getDoctrine()
                        ->getRepository(ApplicationRequests::class)->getRequestsForApplicationsThatUserIsAdminOf($this->getUser()->getId());
        $requests = array();
        $lastApplicationName = "";
        foreach ($applicationRequests as $applicationRequest) { /* @var $applicationRequest ApplicationRequest */
            $thisApplicationName = $applicationRequest['application_name'];
            if ($thisApplicationName != $lastApplicationName) {
                $requests[$thisApplicationName] = 1;
            } else {
                $requests[$thisApplicationName] ++;
            }
            $lastApplicationName = $thisApplicationName;
        }

        $applicationRequests = $requests;

        $markedGroupRequests = $this->getDoctrine()
                        ->getRepository(GroupMembershipNotifications::class)->getNotificationsForUser($this->getUser()->getId());

        $markedApplicationRequests = $this->getDoctrine()
                        ->getRepository(GroupApplicationNotifications::class)->getNotificationsForGroupsUserIsMemberOf($this->getUser()->getId());

        $hasNotifications = count($groupRequests) > 0 ||
                count($applicationRequests) > 0 ||
                count($markedGroupRequests) > 0 ||
                count($markedApplicationRequests) > 0;

        // End notification section //
        //My recent activity
        $recentSimulations = $this->getDoctrine()
                        ->getRepository(RecentSimulations::class)->getRecentSimulationsForUser($this->getUser()->getId(), 15);

        //Application Management
        $applications = $this->getDoctrine()
                        ->getRepository(ApplicationAdmins::class)->getApplicationsUserIsAnAdminOf($this->getUser()->getId());
        //Recent Simulation
        $updates = $this->getDoctrine()
                        ->getRepository(IsimulateUpdates::class)->getRecentUpdates();

        return $this->render('common/rightColumn.html.twig', [
                    'groups' => $this->getUser()->getGroupMembers(),
                    'groupRequests' => $groupRequests,
                    'applicationRequests' => $applicationRequests,
                    'markedGroupRequests' => $markedGroupRequests,
                    'markedApplicationRequests' => $markedApplicationRequests,
                    'hasNotifications' => $hasNotifications,
                    'groupRequestsCount' => count($groupRequests),
                    'applicationRequestsCount' => count($applicationRequests),
                    'recentSimulations' => $recentSimulations,
                    'recentSimulationsCount' => count($recentSimulations),
                    'applications' => $applications,
                    'updates' => $updates,
        ]);
    }

}
