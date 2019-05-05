<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\GroupMembers;

class DashboardController extends AbstractController {

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index() {

        return $this->render('dashboard/index.html.twig');
    }

    /**
     * @Route("/submitFeedback", name="submit_feedback")
     */
    public function submitFeedback(Request $request, \Swift_Mailer $mailer) {
        if ($request->isMethod('post') && $request->isXmlHttpRequest()) {
            if (strlen($request->get('feedback')) == 0) {
                return new Response('Please enter a comment to submit your feedback.');
            }

            $message = (new \Swift_Message('iSimulate Feedback from ' . $this->getUser()->getFirstName() . ' ' . $this->getUser()->getLastName()))
                    ->setFrom('info@dbaquincy.com')
                    ->setTo('hariom4037@gmail.com')
//                    ->setTo([
//                        'rdetweiler@worldbank.org',
//                        'aazeddine@worldbank.org',
//                        'tbui5@worldbank.org'
//                    ])
                    ->setBody(
                    $this->renderView(
                            'emails/submitFeedback.html.twig', ['feedback' => $request->get('feedback')]
                    ), 'text/html'
            );

            if ($mailer->send($message)) {
                return new Response("<b>Your feedback has been submitted.  If your comments require a response, we will get back to you as soon as possible.  Thanks!</b>");
            }
            return new Response("<b>We're sorry, but there has been an error submitting your feedback.  Please try again.</b>");
        }
    }

    /**
     * @Route("/inviteFriend", name="invite_friend")
     */
    public function inviteFriend(Request $request, \Swift_Mailer $mailer) {
        if ($request->isMethod('post')) {

            if (strlen($request->get('emails')) == 0) {
                $jsonResponse[] = ['inviteResponse', 'Please enter email address.'];
                $response->headers->set("X-JSON", '(' . json_encode($jsonResponse) . ')');
                return $response;
            }

            $subject = $this->getUser()->getFirstName() . " " . $this->getUser()->getLastName() . " has invited you to use iSimulate @ World Bank";
            $emails = explode(',', $request->get('emails'));
            $message = (new \Swift_Message($subject))
                    ->setFrom($this->getUser()->getEmail())
                    ->setTo($emails)
                    ->setBody(
                    $this->renderView(
                            'emails/inviteFriend.html.twig'
                    ), 'text/html'
            );
            if ($numSuccess = $mailer->send($message)) {
                $entityManager = $this->getDoctrine()->getManager();
                $user = $this->getUser();
                $user->setInvitesRemaining($this->getUser()->getInvitesRemaining() - count($emails));
                $entityManager->persist($user);
                $entityManager->flush();
                if ($request->isXmlHttpRequest()) {
                    $response = new Response();
                    $jsonResponse[] = array("invitesRemaining", $user->getInvitesRemaining() . " Left");
                    $jsonResponse[] = array("inviteResponse", "Invite sent successfully.");
                    $response->headers->set("X-JSON", '(' . json_encode($jsonResponse) . ')');
                    return $response;
                } else {
                    return new Response("Invite sent.");
                }
            }
            $response = new Response();
            $jsonResponse[] = array("inviteResponse", "There has been an error while sending your invite.  Please try again.");
            $response->headers->set("X-JSON", '(' . json_encode($jsonResponse) . ')');
            return $response;
        }

        return $this->render('dashboard/inviteFriend.html.twig');
    }

}
