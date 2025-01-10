<?php

namespace App\Controller\Admin;

use App\Classe\Mail;
use App\Entity\FVOrder;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Request;

class FVOrderCrudController extends AbstractCrudController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public static function getEntityFqcn(): string
    {
        return FVOrder::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Commande')
            ->setEntityLabelInPlural('Commandes')
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        $show = Action::new('Afficher')->linkToCrudAction('show');
        return $actions
            ->add(Crud::PAGE_INDEX, $show)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
        ;
    }

    public function changeState($order, $state)
    {
        $order->setState($state);
        $this->entityManager->flush();
        $this->addFlash(
            'success',
            'Statut de la commande mis à jour'
        );

        $user = $order->getUser();
        $vars = [
            'firstname' => $user->getFirstname(),
            'id_order' => $order->getId()
        ];
        
        if($state == 3){
            $subject = 'Commande n°'.$order->getId().' en cours de traitement';
        }elseif($state == 4){
            $subject = 'Commande n°'.$order->getId().' en cours de livraison';
        }elseif($state == 5){
            $subject = 'Commande n°'.$order->getId().' Livrée';
        }elseif($state == 6){
            $subject = 'Annulation de la commande n°'.$order->getId();
        }

        $mail = new Mail;
        $mail->send($user->getEmail(), $user->getFirstname(), $subject, 'order_state_'.$state.'.html', $vars);
    }

    public function show(AdminContext $context, AdminUrlGenerator $adminUrlGenerator, Request $request)
    {
        $order = $context->getEntity()->getInstance();

        $url = $adminUrlGenerator->setController(self::class)->setAction('show')->setEntityId($order->getId())->generateUrl();

        if($request->get('state')){
            $this->changeState($order, $request->get('state'));
        }
        
        return $this->render('admin/order.html.twig', [
            'order' => $order,
            'current_url' => $url
        ]);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateField::new('createdAt', 'Date de création'),
            NumberField::new('state', 'Statut')->setTemplatePath('admin/state.html.twig'),
            AssociationField::new('user', 'Utilisateur'),
            TextField::new('carrierName', 'Formule de livraison'),
            NumberField::new('totalTva', 'Total TVA'),
            NumberField::new('totalWt', 'Total T.T.C')
        ];
    }
}
