<?php

namespace App\Controller\Dev\Admin;

use App\Entity\User\Bot;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BotCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Bot::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('type'),
            TextField::new('token'),
            TextField::new('name'),
            IntegerField::new('projectId'),
            BooleanField::new('active'),
            TextField::new('webhookUri'),
        ];
    }
}
