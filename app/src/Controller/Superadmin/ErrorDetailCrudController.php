<?php

namespace App\Controller\Superadmin;

use App\Entity\ErrorDetail;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ErrorDetailCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ErrorDetail::class;
    }
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW)
            ->disable(Action::EDIT);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextEditorField::new('detail'),
            TextEditorField::new('origin'),
            TextField::new('searchTerm'),
            Field::new('createdAt')
        ];
    }
}
