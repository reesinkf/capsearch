<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{
    /**
     * @Route("/message", name="message", methods="POST")
     */
    public function index(Request $request)
    {
//        $name = $request->query->get("name");
//        $message = $request->query->get("message");

        $name = $request->request->get('name');
        $message = $request->request->get('message');

        return $this->render('message/index.html.twig', ["name" => $name,
            "message" => $message]);
    }
}
