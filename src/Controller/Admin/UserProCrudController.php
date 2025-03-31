<?php

namespace App\Controller\Admin;

use App\Entity\UserPro;
use App\Service\UserProService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserProCrudController extends AbstractCrudController
{
    private UserProService $userProService;
    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(
        UserProService $userProService,
        AdminUrlGenerator $adminUrlGenerator
    ) {
        $this->userProService = $userProService;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return UserPro::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Demande Pro')
            ->setEntityLabelInPlural('Demandes Pro')
            ->setDefaultSort(['requestedAt' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('user', 'Utilisateur');
        yield TextField::new('phone', 'Téléphone');
        yield TextField::new('companyName', 'Société');
        yield TextField::new('companyAdress', 'Adresse');
        yield BooleanField::new('isValidated', 'Validé');
        yield DateTimeField::new('requestedAt', 'Demande le')
            ->setFormTypeOption('disabled', true);
    }

    public function configureActions(Actions $actions): Actions
    {
        $validateAction = Action::new('validate', 'Valider')
            ->linkToCrudAction('validateUserPro')
            ->displayIf(fn (UserPro $userPro) => !$userPro->isValidated());
            
        $rejectAction = Action::new('reject', 'Rejeter')
            ->linkToCrudAction('rejectUserPro')
            ->displayIf(fn (UserPro $userPro) => !$userPro->isValidated());
            
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_DETAIL, $validateAction)
            ->add(Crud::PAGE_DETAIL, $rejectAction);
    }
    
    public function validateUserPro(AdminContext $context): RedirectResponse
    {
        $userPro = $context->getEntity()->getInstance();
        $this->userProService->validateUserPro($userPro);
        
        $this->addFlash('success', 'Le statut professionnel a été validé avec succès.');
        
        return $this->redirect($this->adminUrlGenerator
            ->setController(self::class)
            ->setAction(Action::INDEX)
            ->generateUrl());
    }
    
    public function rejectUserPro(AdminContext $context): RedirectResponse
    {
        $userPro = $context->getEntity()->getInstance();
        $this->userProService->rejectUserProRequest($userPro);
        
        $this->addFlash('success', 'La demande de statut professionnel a été rejetée.');
        
        return $this->redirect($this->adminUrlGenerator
            ->setController(self::class)
            ->setAction(Action::INDEX)
            ->generateUrl());
    }
}
