<?php

namespace App\Controller\Superadmin\Messages;

use App\Entity\Message\UserMessages;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class UserMessagesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserMessages::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new("id"),
            TextField::new('sender.name', "Name"),
            AssociationField::new('sender'),
            Field::new('createdAt')->setDisabled(true),
        ];
    }


    public function configureActions(Actions $actions): Actions
    {
        $replyMessage = Action::new('reply', 'Reply', 'fa fa-reply')
            ->linkToCrudAction('replyMessage')
        ;

        $actions
            ->add(Crud::PAGE_INDEX, $replyMessage)
            ->disable(Action::DELETE)
            ->disable(Action::NEW)
            ->disable(Action::EDIT)
            ;

        return $actions;
    }

    /**
     * Custom request to get not null sender
     * @param SearchDto $searchDto
     * @param EntityDto $entityDto
     * @param FieldCollection $fields
     * @param FilterCollection $filters
     * @return QueryBuilder
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->container->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $prefix = $queryBuilder->getRootAliases()[0];

        $queryBuilder
            ->andWhere(sprintf("%s.sender IS NOT NULL", $prefix))
        ;
        return $queryBuilder;
    }

    #[Template("superadmin/messages/reply.html.twig")]
    public function replyMessage(AdminContext $context)
    {
        $message = $context->getEntity()->getInstance();

        return ['message' => $message];
    }
}
