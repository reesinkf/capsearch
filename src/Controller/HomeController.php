<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request) {

        $form = $this->createFormBuilder([])
            ->add('name', TextType::class,
                ['label' => 'Name'])
            ->add('gender', TextType::class,
                ['label' => 'Gender'])
            ->add('over18', CheckboxType::class,
                [ 'label' => 'I am over 18',
                'required' => false
                ])
            ->add('gender', ChoiceType::class, [
                'choices'  => [
                    'Male' => "Male",
                    'Female' => "Female"]
                ])
            ->add('car', ChoiceType::class, [
                'choices'  => [
                    'Mercedes' => "Mercedes",
                    'Audi' => "Audi",
                    'Saab' => "Saab",
                    'Ford' => "Ford"]
            ])
            ->add('message', TextType::class,
                ['label' => 'Message'])
            ->add('save', SubmitType::class,
                ['label' => 'Post'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            return $this->render('message/index.html.twig', [
                "name" => $data['name'],
                "gender" => $data['gender'],
                "over18" => $data['over18'],
                "car" => $data['car'],
                "message" => $data['message'],
            ]);
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
