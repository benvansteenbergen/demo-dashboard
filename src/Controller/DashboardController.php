<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Orders;
use Symfony\Component\Serializer\SerializerInterface;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index()
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController'
        ]);
    }
    /**
     * @Route("/dashboard/check", name="dashboard_check")
     */
    public function getNewOrders(SerializerInterface $serializer)
    {
        $orderRepo = $this->getDoctrine()->getRepository(Orders::class);
        $order = $orderRepo->findOneBy(['processed' => 0]);

        if (!$order) {
            return new JsonResponse(['success' => false], 200);
        }

        $orderJson = $serializer->serialize($order, 'json');

        try {
            $entityManager = $this->getDoctrine()->getManager();
            $order->setProcessed(true);
            $entityManager->persist($order);
            $entityManager->flush();

        } catch (\Exception $e){
            return new JsonResponse(['success' => false, 'message' => 'cannot process order'], 400);
        }

        return new JsonResponse(['success'=>true,'data' => $orderJson], 200);
    }
}
