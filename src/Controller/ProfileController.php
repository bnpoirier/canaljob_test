<?php

namespace App\Controller;

use App\Form\AccountType;
use App\Form\ChangeAccountInformationType;
use App\Form\ChangeAccountPasswordType;
use App\Form\ImportUsersType;
use App\Service\UserImporter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class ProfileController
 * @package App\Controller
 * @Route("/profile")
 */
class ProfileController extends Controller
{
    /**
     * @Route("/", name="profile_index")
     */
    public function index()
    {
        $contacts = $this->getUser()->getContacts();
        return $this->render('profile/index.html.twig', [
            "contacts" => $contacts
        ]);
    }

    /**
     * @Route("/settings", name="profile_settings")
     * @param Request $request
     * @param PasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function settings(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user, ['method' => "PATCH"]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $plainPassword = $user->getPlainPassword();

            if(!empty($plainPassword)){
                $user->setPassword($passwordEncoder->encodePassword($user, $plainPassword));
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "Compte mis à jour !");
            return $this->redirectToRoute('profile_index');
        }
        return $this->render('profile/settings.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/import", name="profile_import")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function importUsers(Request $request, UserImporter $userImporter)
    {
        $form = $this->createForm(ImportUsersType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            // L'utilisateur qui importe la liste d'utilisateurs est récupérée.
            $user = $this->getUser();

            // Le CSV est passé à l'importeur
            $data = $form->getData();
            $users = $userImporter->getUsersFromCSV($data['csv']);
            // Ces Users sont ajoutés à la liste de contacts de l'utilisateur qui importe
            $user->addContacts($users);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Comptes importés ! Les mots de passe par défaut sont les prénoms des personnes');
            return $this->redirectToRoute('profile_index');
        }
        return $this->render('profile/import.html.twig', ['form' => $form->createView()]);
    }
}
