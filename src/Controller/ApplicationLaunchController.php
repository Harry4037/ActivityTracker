<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Entity;
use App\Entity\Groups;
use App\Entity\Application;
use App\Entity\GroupMembers;
use App\Entity\GroupApplications;
use App\Entity\EntityInApplication;
use App\Entity\RecentSimulations;
use App\Service\ApplicationRunner;

//use App\Controller\ApplicationRunner;

class ApplicationLaunchController extends AbstractController {

    /**
     * @Route("/group/{groupName}/application/{application}/countryChooser", name="application_launch_index")
     */
    public function index(Request $request) {
        if ($request->isMethod('post')) {
            $entity = $this->getDoctrine()
                            ->getRepository(Entity::class)->find($request->get('entityID'));
            if ($entity) {
                return $this->redirectToRoute('displayEntity', [
                            'groupName' => $request->get('groupName'),
                            'application' => $request->get('application'),
                            'entityCode' => $entity->getEntityCode(),
                ]);
            }
        } else {
            return new Response(404);
        }
    }

    /**
     * @Route("/group/{groupName}/application/{application}/entity/{entityCode}", name="displayEntity")
     */
    public function displayGrid(Request $request) {
        $appRunner = new ApplicationRunner(1, 1, 1);
        dd($appRunner);
        if ($request->get('groupName') && $request->get('application') && $request->get('entityCode')) {
            $group = $this->getDoctrine()
                            ->getRepository(Groups::class)->findOneBy(["groupName" => $request->get('groupName')]);

            if (!$group) {
                new Response(404);
            }
            $app = $this->getDoctrine()
                            ->getRepository(Application::class)->findOneBy(["applicationName" => $request->get('application')]);

            if (!$app) {
                new Response(404);
            }
            $groupMember = $this->getDoctrine()
                            ->getRepository(GroupMembers::class)->findOneBy(["groupID" => $group->getId(), "userID" => $this->getUser()]);

            if (!$groupMember) {
                new Response(404);
            }
            $entity = $this->getDoctrine()
                            ->getRepository(Entity::class)->findOneBy(["entityCode" => $request->get('entityCode')]);

            if (!$entity) {
                new Response(404);
            }
            $groupApplicationSubscribe = $this->getDoctrine()
                            ->getRepository(GroupApplications::class)->findOneBy(["groupID" => $group->getId(), "applicationID" => $app->getId()]);

            if (!$groupApplicationSubscribe) {
                new Response(404);
            }
            $entityInApplication = $this->getDoctrine()
                            ->getRepository(EntityInApplication::class)->findOneBy(["entityID" => $entity->getId(), "applicationID" => $app->getId()]);

            if (!$entityInApplication) {
                new Response(404);
            }

            return $this->render('dashboard/displayGrid.html.twig', [
                        'groupName' => $request->get('groupName'),
                        'applicationName' => $request->get('application'),
                        'entity' => $entity,
                        'entityCode' => $request->get('entityCode')
            ]);
        }
    }

    /**
     * @Route("/group/{groupName}/application/{application}/entity/{entityCode}/load", name="loadGrid")
     */
    public function loadGrid(Request $request) {
        if ($request->get('groupName') && $request->get('application') && $request->get('entityCode')) {
            $group = $this->getDoctrine()
                            ->getRepository(Groups::class)->findOneBy(["groupName" => $request->get('groupName')]);

            if (!$group) {
                throw new \Exception("The group you are working with was not found.");
            }
            $app = $this->getDoctrine()
                            ->getRepository(Application::class)->findOneBy(["applicationName" => $request->get('application')]);

            if (!$app) {
                throw new \Exception("The application you are working with was not found.");
            }
            $groupMember = $this->getDoctrine()
                            ->getRepository(GroupMembers::class)->findOneBy(["groupID" => $group->getId(), "userID" => $this->getUser()]);

            if (!$groupMember) {
                throw new \Exception("You are not allowed to view data in this group.");
            }
            $entity = $this->getDoctrine()
                            ->getRepository(Entity::class)->findOneBy(["entityCode" => $request->get('entityCode')]);

            if (!$entity) {
                throw new \Exception("The entity you are working with was not found.");
            }
            $groupApplicationSubscribe = $this->getDoctrine()
                            ->getRepository(GroupApplications::class)->findOneBy(["groupID" => $group->getId(), "applicationID" => $app->getId()]);

            if (!$groupApplicationSubscribe) {
                throw new \Exception("The group " . $request->get('groupName') . " does not subscribe to the " . $request->get('application') . " application.");
            }
            $entityInApplication = $this->getDoctrine()
                            ->getRepository(EntityInApplication::class)->findOneBy(["entityID" => $entity->getId(), "applicationID" => $app->getId()]);

            if (!$entityInApplication) {
                throw new \Exception("The " . $request->get('application') . " application does not include " . $request->get('entityCode'));
            }

            // add entity to recentSimulations table for user
            $entityManager = $this->getDoctrine()->getManager();

            $sim = new RecentSimulations();
            $sim->setUserID($this->getUser());
            $sim->setGroupID($group);
            $sim->setApplicationID($app);
            $sim->setEntityID($entity);
            $entityManager->persist($sim);
            $entityManager->flush();
dd("hi........");
//            $applicationRunner = new ApplicationRunner($group, $app, $entity);
//            $rs = $runner->load();
//            dd("end");


            $toReturn["grids"] = '';
            $toReturn["success"] = 1;
            $toReturn["updateAllowed"] = '';
            $toReturn["permissions"] = '';
            $toReturn["isAggregate"] = '';
            $toReturn["transactionID"] = '';
            return $this->json($toReturn);
        } else {
            throw new \Exception("Incorrect transaction URL");
        }
    }

    /**
     * @Route("/group/{groupName}/application/{application}/entity/{entityCode}/checkSync", name="checkSync")
     */
    public function checkSync(Request $request) {
        if ($request->get('groupName') && $request->get('application') && $request->get('entityCode')) {
            $group = $this->getDoctrine()
                            ->getRepository(Groups::class)->findOneBy(["groupName" => $request->get('groupName')]);

            if (!$group) {
                throw new \Exception("The group you are working with was not found.");
            }
            $app = $this->getDoctrine()
                            ->getRepository(Application::class)->findOneBy(["applicationName" => $request->get('application')]);

            if (!$app) {
                throw new \Exception("The application you are working with was not found.");
            }
            $groupMember = $this->getDoctrine()
                            ->getRepository(GroupMembers::class)->findOneBy(["groupID" => $group->getId(), "userID" => $this->getUser()]);

            if (!$groupMember) {
                throw new \Exception("You are not allowed to view data in this group.");
            }
            $entity = $this->getDoctrine()
                            ->getRepository(Entity::class)->findOneBy(["entityCode" => $request->get('entityCode')]);

            if (!$entity) {
                throw new \Exception("The entity you are working with was not found.");
            }
            $groupApplicationSubscribe = $this->getDoctrine()
                            ->getRepository(GroupApplications::class)->findOneBy(["groupID" => $group->getId(), "applicationID" => $app->getId()]);

            if (!$groupApplicationSubscribe) {
                throw new \Exception("The group " . $request->get('groupName') . " does not subscribe to the " . $request->get('application') . " application.");
            }
            $entityInApplication = $this->getDoctrine()
                            ->getRepository(EntityInApplication::class)->findOneBy(["entityID" => $entity->getId(), "applicationID" => $app->getId()]);

            if (!$entityInApplication) {
                throw new \Exception("The " . $request->get('application') . " application does not include " . $request->get('entityCode'));
            }



            $response["isAggregate"] = false;
            $response["isUpdate"] = false;
            $response["userName"] = '';
            $response["comment"] = '';
            $response["transactionID"] = '';
            return $this->json($response);
        } else {
            throw new \Exception("Incorrect transaction URL");
        }
    }

    /**
     * @Route("/group/{groupName}/application/{application}/entity/{entityCode}/solve", name="solveGrid")
     */
    public function solveGrid(Request $request) {
        set_time_limit(0);
        if ($request->get('groupName') && $request->get('application') && $request->get('entityCode')) {
            $group = $this->getDoctrine()
                            ->getRepository(Groups::class)->findOneBy(["groupName" => $request->get('groupName')]);

            if (!$group) {
                throw new \Exception("The group you are working with was not found.");
            }
            $app = $this->getDoctrine()
                            ->getRepository(Application::class)->findOneBy(["applicationName" => $request->get('application')]);

            if (!$app) {
                throw new \Exception("The application you are working with was not found.");
            }
            $groupMember = $this->getDoctrine()
                            ->getRepository(GroupMembers::class)->findOneBy(["groupID" => $group->getId(), "userID" => $this->getUser()]);

            if (!$groupMember) {
                throw new \Exception("You are not allowed to view data in this group.");
            }
            $entity = $this->getDoctrine()
                            ->getRepository(Entity::class)->findOneBy(["entityCode" => $request->get('entityCode')]);

            if (!$entity) {
                throw new \Exception("The entity you are working with was not found.");
            }
            $groupApplicationSubscribe = $this->getDoctrine()
                            ->getRepository(GroupApplications::class)->findOneBy(["groupID" => $group->getId(), "applicationID" => $app->getId()]);

            if (!$groupApplicationSubscribe) {
                throw new \Exception("The group " . $request->get('groupName') . " does not subscribe to the " . $request->get('application') . " application.");
            }
            $entityInApplication = $this->getDoctrine()
                            ->getRepository(EntityInApplication::class)->findOneBy(["entityID" => $entity->getId(), "applicationID" => $app->getId()]);

            if (!$entityInApplication) {
                throw new \Exception("The " . $request->get('application') . " application does not include " . $request->get('entityCode'));
            }

            $gridData = $request->get('gridData');
            $usersData = json_decode($gridData, true);
            //start application runner

            $toReturn["success"] = 1;
            $toReturn["isAggregate"] = '';
            $toReturn["transactionID"] = '';
            $toReturn["updateAllowed"] = '';
            $toReturn["permissions"] = '';
            $toReturn["grids"] = '';

            // get any system generated message
            $transaction = '';
            $toReturn["msg"] = '';
            return $this->json($toReturn);
        } else {
            throw new \Exception("Incorrect transaction URL");
        }
    }

    /**
     * @Route("/group/{groupName}/application/{application}/entity/{entityCode}/update", name="updateGrid")
     */
    public function updateGrid(Request $request) {
        set_time_limit(0);
        if ($request->get('groupName') && $request->get('application') && $request->get('entityCode')) {
            $group = $this->getDoctrine()
                            ->getRepository(Groups::class)->findOneBy(["groupName" => $request->get('groupName')]);

            if (!$group) {
                throw new \Exception("The group you are working with was not found.");
            }
            $app = $this->getDoctrine()
                            ->getRepository(Application::class)->findOneBy(["applicationName" => $request->get('application')]);

            if (!$app) {
                throw new \Exception("The application you are working with was not found.");
            }
            $groupMember = $this->getDoctrine()
                            ->getRepository(GroupMembers::class)->findOneBy(["groupID" => $group->getId(), "userID" => $this->getUser()]);

            if (!$groupMember) {
                throw new \Exception("You are not allowed to view data in this group.");
            }
            $entity = $this->getDoctrine()
                            ->getRepository(Entity::class)->findOneBy(["entityCode" => $request->get('entityCode')]);

            if (!$entity) {
                throw new \Exception("The entity you are working with was not found.");
            }
            $groupApplicationSubscribe = $this->getDoctrine()
                            ->getRepository(GroupApplications::class)->findOneBy(["groupID" => $group->getId(), "applicationID" => $app->getId()]);

            if (!$groupApplicationSubscribe) {
                throw new \Exception("The group " . $request->get('groupName') . " does not subscribe to the " . $request->get('application') . " application.");
            }
            $entityInApplication = $this->getDoctrine()
                            ->getRepository(EntityInApplication::class)->findOneBy(["entityID" => $entity->getId(), "applicationID" => $app->getId()]);

            if (!$entityInApplication) {
                throw new \Exception("The " . $request->get('application') . " application does not include " . $request->get('entityCode'));
            }
//Application runner
            $toReturn["success"] = 1;
            $toReturn["transactionID"] = $request->get('id');
            return $this->json($toReturn);
        } else {
            throw new \Exception("Incorrect transaction URL");
        }
    }

}
