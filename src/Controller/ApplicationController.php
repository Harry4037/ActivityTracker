<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\GroupMembers;
use App\Entity\Groups;
use App\Entity\GroupApplications;
use App\Entity\Application;
use App\Entity\Entity;
use App\Entity\EntityInApplication;

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
        dd("application subscription");
        $group = GroupPeer::retrieveByGroupName($this->getRequestParameter('groupName'));
        /* @var $group Group */
        $this->forward404Unless($group);

        // check to make sure user is an admin of group		
        $this->groupMember = GroupMemberPeer::retrieveGroupAdmin($this->getUser()->getUserID(), $this->getRequestParameter('groupName'));
        $this->forwardUnless($this->groupMember, 'group', 'show');
        $this->group = $group;

        // get most popular applications
        $c = new Criteria();
        $c->addAsColumn('groupCount', 'count(' . GroupApplicationPeer::GROUPID . ')');
        $c->addGroupByColumn(ApplicationPeer::APPLICATIONID);
        $c->addDescendingOrderByColumn("groupCount");
        $c->addAscendingOrderByColumn(ApplicationPeer::APPLICATIONNAME);
        $c->setLimit(5);
        $this->popularApplications = GroupApplicationPeer::doSelectJoinApplication($c);

        // get current application subscriptions
        $pager = new sfPropelPager('GroupApplication', sfConfig::get('app_pager_myGroupApplications_max'));
        $c = new Criteria();
        $c->add(GroupApplicationPeer::GROUPID, $group->getGroupid());
        $c->addAscendingOrderByColumn(ApplicationPeer::APPLICATIONNAME);
        $pager->setCriteria($c);
        $pager->setPage($this->getRequestParameter('pageNumber'));
        $pager->setPeerMethod('doSelectJoinApplication');
        $pager->init();
        $this->pager = $pager;

        // get current application requests		
        $c = new Criteria();
        $c->add(ApplicationRequestPeer::GROUPID, $group->getGroupid());
        $c->addAscendingOrderByColumn(ApplicationRequestPeer::CREATED_AT);
        $this->applicationRequests = ApplicationRequestPeer::doSelectJoinApplication($c);
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

    /**
     * @Route("/group/{groupName}/application/{applicationName}/entityList", name="applicationLaunchStep3")
     */
    public function applicationLaunchStep3(Request $request) {
        if ($request->isXmlHttpRequest()) {
            $application = $this->getDoctrine()
                            ->getRepository(Application::class)->findOneBy(["applicationName" => $request->get('applicationName')]);
            if ($application) {
                $entities = $this->getDoctrine()
                                ->getRepository(EntityInApplication::class)->findBy(["applicationID" => $application->getId()]);
                if ($entities) {
                    foreach ($entities as $entity) {
                        $entityList[$entity->getEntityId()->getId()] = $entity->getEntityId()->getEntityName();
                    }
                } else {
                    $entityList = [];
                }

                return $this->render('dashboard/applicationLaunch3.html.twig', [
                            'application' => $application,
                            'entityList' => $entityList,
                            'groupName' => $request->get('groupName'),
                ]);
            } else {
                return new Response(404);
            }
        } else {
            return new Response(404);
        }
    }

}
