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

        $name = $request->request->get('name');
        $gender = $request->request->get('gender');
        $over18 = $request->request->get('over18');
        $car = $request->request->get('car');
        $message = $request->request->get('message');

        return $this->render('message/index.html.twig', [
            "name" => $name,
            "gender" => $gender,
            "over18" => $over18,
            "car" => $car,
            "message" => $message
        ]);
    }
}
