<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);

    }

    private function loadUsers(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$username,$firstName,$lastName,$email,$password,$affiliation,$timezone,$displayName,$invitesRemaining,$showGetStartedBox,$iChartInternalAccess,$activated,$loginAttempts,$newPasswordCode,$plainPassword,$resetPassword,$lastLoginAttempt,$newPasswordTime,$created_at,$roles]) {
            $user = new Users();
            $user->setUsername($username);
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setEmail($email);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setAffiliation($affiliation);
            $user->setTimezone($timezone);
            $user->setDisplayName($displayName);
            $user->setInvitesRemaining($invitesRemaining);
            $user->setShowGetStartedBox($showGetStartedBox);
            $user->setIChartInternalAccess($iChartInternalAccess);
            $user->setActivated($activated);
            $user->setLoginAttempts($loginAttempts);
            $user->setNewPasswordCode($newPasswordCode);
            $user->setPlainPassword($plainPassword);
            $user->setResetPassword($resetPassword);
            $user->setActivationCode($resetPassword);
            $user->setLastLoginAttempt(new \DateTime($lastLoginAttempt));
            $user->setNewPasswordTime(new \DateTime($newPasswordTime));
            $user->setCreatedAt(new \DateTime($created_at));
            $user->setRoles($roles);
            $manager->persist($user);
            $this->addReference($username, $user);
        }

        $manager->flush();
    }


    private function getUserData(): array
    {
        return [
            [ 'nthusiast', 'David', 'Horowitz','nthusiast@gmail.com','abdul@123','World Bank','America/New_York','user',30,0,0,2,0,'793340082578785818844868254843892197741949066','MT1hXX5nDAKh7UC8EVV8SwkTNsXAxrjvraYMX6MNfr88L','fdgdfgdf','2012-03-15 14:00:11','2017-05-05 21:00:12','2007-05-10 23:45:23', ['USER']],
        ];
    }


}
