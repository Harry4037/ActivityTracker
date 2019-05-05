<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Groups;
use App\Entity\GroupMembers;

class GroupController extends AbstractController {

    /**
     * @Route("/group/create", name="group_create")
     */
    public function create(Request $request) {

        $form = $this->createFormBuilder(new Groups())
                ->add('groupName', TextType::class, ['label' => 'Group Name:', 'required' => FALSE])
                ->add('description', TextareaType::class, ['label' => 'Group Description:', 'required' => FALSE])
                ->add('approveToJoin', ChoiceType::class, [
                    'choices' => [
                        'Yes' => true,
                        'No' => false
                    ],
                    'label' => 'Requires Approval to Join?'
                ])
                ->add('commit', SubmitType::class, ['label' => 'Create Group!'])
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $group = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $group->setPublicView(0);
            $group->setCreatedAt(new \DateTime());
            $entityManager->persist($group);
            $entityManager->flush();
            if ($group->getId() > 0) {
                $groupMember = new GroupMembers();
                $groupMember->setGroupID($group);
                $groupMember->setUserID($this->getUser());
                $groupMember->setAdmin(true);
                $groupMember->setPermissionLevel(23);
                $groupMember->setCreatedAt(new \DateTime());
                $entityManager->persist($groupMember);
                $entityManager->flush();
            }
            $this->addFlash('success', 'Congratulations! Your new group is ready to use.');
            return $this->redirectToRoute('group_show', ['groupName' => $group->getGroupName()]);
        }
        return $this->render('group/create.html.twig', [
                    'controller_name' => 'GroupController',
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/group/{groupName}", name="group_show")
     */
    public function show(Request $request) {
        return $this->render('group/show.html.twig');
    }

}
