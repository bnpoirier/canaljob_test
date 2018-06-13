<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class ContactController
 * @package App\Controller
 * @Route("/contact")
 */
class ContactController extends Controller
{
    /**
     * @Route("/search", name="contact_search")
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        $results = $this->getDoctrine()->getRepository('App:User')->search($query);

        return $this->render('contact/search.html.twig', [
            'query' => $query,
            'results' => $results
        ]);
    }

    /**
 * @Route("/add/{contact}", name="contact_add", methods={"POST"})
 * @ParamConverter("contact", class="App:User")
 * @param User $contact
 * @return \Symfony\Component\HttpFoundation\JsonResponse
 */
    public function add(User $contact)
    {
        $user = $this->getUser();
        $user->addContact($contact);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->json(['success' => true]);
    }

    /**
     * @Route("/delete/{contact}", name="contact_delete", methods={"DELETE"})
     * @ParamConverter("contact", class="App:User")
     * @param User $contact
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function delete(User $contact)
    {
        $user = $this->getUser();
        $user->getContacts()->removeElement($contact);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->json(['success' => true]);
    }

    /**
     * @Route("/{user}", name="contact_see")
     * @ParamConverter("user", class="App:User")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function see(User $user)
    {
        return $this->render('contact/see.html.twig', ['user' => $user]);
    }
}
