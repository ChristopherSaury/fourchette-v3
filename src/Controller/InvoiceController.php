<?php

namespace App\Controller;

use App\Repository\FVOrderRepository;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class InvoiceController extends AbstractController
{
    #[Route('/compte/facture/impression/{id_order}', name: 'app_invoice_customer')]
    public function invoiceForCustomer($id_order, FVOrderRepository $fVOrderRepository): Response
    {
        $order = $fVOrderRepository->findOneBy([
            'user' => $this->getUser(),
            'id' => $id_order
        ]);

        if (!$order) {
            return $this->redirectToRoute('app_account');
        }

        $dompdf = new Dompdf();
        $HTML = $this->renderView('invoice/index.html.twig', [
            'order' => $order
        ]);
        $dompdf->loadHtml($HTML);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('facture.pdf', [
            'Attachment' => false
        ]);
        exit();
        return $this->render('invoice/index.html.twig', [
            'controller_name' => 'InvoiceController',
        ]);
    }

    #[Route('/admin/facture/impression/{id_order}', name: 'app_invoice_admin')]
    public function invoiceForAdmin($id_order, FVOrderRepository $fVOrderRepository): Response
    {
        $order = $fVOrderRepository->findOneById($id_order);

        if (!$order) {
            return $this->redirectToRoute('admin');
        }

        $dompdf = new Dompdf();
        $HTML = $this->renderView('invoice/index.html.twig', [
            'order' => $order
        ]);
        $dompdf->loadHtml($HTML);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('facture.pdf', [
            'Attachment' => false
        ]);
        exit();
        return $this->render('invoice/index.html.twig', [
            'controller_name' => 'InvoiceController',
        ]);
    }
}
