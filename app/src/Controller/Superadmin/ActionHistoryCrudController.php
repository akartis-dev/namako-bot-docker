<?php

namespace App\Controller\Superadmin;

use App\Entity\ActionHistory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ActionHistoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ActionHistory::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::DELETE)
            ->disable(Action::NEW)
            ->disable(Action::EDIT)
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextField::new('type'),
            TextField::new('status'),
            TextField::new('customer.firstName', 'Firstname'),
            TextField::new('customer.lastname', 'Lastname'),
            AssociationField::new('customer')
        ];
    }

}
