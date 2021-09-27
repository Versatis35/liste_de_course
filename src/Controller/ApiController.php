<?php

namespace App\Controller;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/liste/", name="api_liste_course" ,methods={"GET"})
     */
    public function index(ItemRepository $repo): Response
    {
        return $this->json($repo->findAll());
    }

    /**
     * @Route("/api/liste/", name="api_add_course" ,methods={"POST"})
     */
    public function ajouter(Request $request,EntityManagerInterface $em): Response
    {
        $json =$request->getContent();
        $obj = json_decode($json);

        $item  = new Item();
        $item->setNom($obj->nom);
        $item->setIsChecked($obj->isChecked);
        $em->persist($item);
        $em->flush();
        return $this->json($item);
    }

    /**
     * @Route("/api/liste/{id}", name="api_check_course" ,methods={"PUT"})
     */
    public function check(Item $item,Request $request,EntityManagerInterface $em): Response
    {
        $json =$request->getContent();
        $obj = json_decode($json);

        $item->setIsChecked($obj->isChecked);
        $em->persist($item);
        $em->flush();
        return $this->json($item);
    }

    /**
     * @Route("/api/liste/{id}", name="api_enlever_course" ,methods={"DELETE"})
     */
    public function delete(Item $item,EntityManagerInterface $em): Response
    {
        $em->remove($item);
        $em->flush();
        return $this->json($item);
    }

    /**
     * @Route("/Accueil", name="invite" ,methods={"GET"})
     */
    public function accueil(): Response
    {
        return $this->render('main/index.html.twig');
    }
}
