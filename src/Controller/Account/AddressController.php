<?php

namespace App\Controller\Account;

use App\Entity\FVAddress;
use App\Form\FVAdressUserType;
use App\Repository\FVAddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddressController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/compte/mes-adresses', name:'app_account_addresses' )]
    public function index(): Response 
    {
        return $this->render('account/address/index.html.twig');

    }

    #[Route('/compte/adresse/ajouter/{id}', name:'app_account_addresses_form', defaults:[ 'id' => null ] )]
    public function form(Request $request, $id, FVAddressRepository $fVAddressRepository): Response 
    {
        if($id){
            $address = $fVAddressRepository->findOneById($id);
            if(!$address OR $address->getUser() != $this->getUser()){
                return $this->redirectToRoute('app_account_addresses');
            }
        }else{
            $address = new FVAddress;
            $address->setUser($this->getUser());
        }
        
        $form = $this->createForm(FVAdressUserType::class, $address);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($address);
            $this->entityManager->flush();
            
            $this->addFlash(
                'success-address',
                'Votre adresse est correctement sauvegardé'
            );

            return $this->redirectToRoute('app_account_addresses');
        }

        return $this->render('account/address/form.html.twig',[
            'addressForm' => $form->createView()
        ]);

    }

    #[Route('/compte/adresse/supprimer/{id}', name:'app_account_addresses_delete' )]
    public function delete($id, FVAddressRepository $fVAddressRepository){
        
            $address = $fVAddressRepository->findOneById($id);
            if(!$address OR $address->getUser() != $this->getUser()){
                return $this->redirectToRoute('app_account_addresses');
            }
            $this->addFlash(
                'success-address',
                'Votre adresse est correctement supprimée'
            );
            $this->entityManager->remove($address);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_account_addresses');
    }

}