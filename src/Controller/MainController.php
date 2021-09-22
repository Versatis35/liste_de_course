<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use ContainerFhdQFxS\getItemRepositoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(ItemRepository $repo, Request $request): Response
    {
        $item = new Item();
        $formItem = $this->createForm(ItemType::class,$item);
        $formItem->handleRequest($request);
        if ($formItem->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $item->setIsChecked(false);
            $em->persist($item);
            $em->flush();
            unset($formItem);
            unset($item);
            $item = new Item();
            $formItem = $this->createForm(ItemType::class,$item);
        }
        $list = $repo->findAll();
        return $this->render('main/index.html.twig', [
            'list' => $list,
            'formItem' => $formItem->createView()
        ]);
    }

    /**
     * @Route("/deleteItem/{id}", name="delete_item")
     */
    public function deleteItem(ItemRepository $repo, $id): Response
    {
        $item = $repo->findOneBy(
            [
                'id'=>$id
            ]
        );
        $em = $this->getDoctrine()->getManager();
        $em->remove($item);
        $em->flush();
        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/checkItem/{id}", name="check_item")
     */
    public function checkItem(ItemRepository $repo, $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $item = $entityManager->getRepository(Item::class)->find($id);
        $item->setIsChecked(1);
        $entityManager->flush();
        return $this->redirectToRoute('index');
    }
}
