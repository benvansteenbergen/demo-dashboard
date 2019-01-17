<?php

namespace App\Controller;

use App\Entity\Orders;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class NotificationReceiverController extends AbstractController
{
    /**
     * @Route("/notification/receiver", name="notification_receiver")
     */
    public function index(Request $request)
    {

        $orderNumber = $request->get('ordernumber');
        $name = $request->get('name');

        if ($orderNumber && $name) {

            try {
                $entityManager = $this->getDoctrine()->getManager();
                $order = new Orders();
                $order->setName($name);
                $order->setOrdernumber($orderNumber);
                $order->setProcessed(0);
                $entityManager->persist($order);

                $entityManager->flush();

                return new JsonResponse(['success'=>true], 200);

            } catch (\Exception $e) {
                return new JsonResponse(['success' => false, 'message'=>$e->getMessage()], 400);
            }
        }

        return new JsonResponse(['success' => false, 'message'=>'Invalid Parameters'], 400);
    }
}
