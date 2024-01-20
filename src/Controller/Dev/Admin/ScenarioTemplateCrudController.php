<?php

namespace App\Controller\Dev\Admin;

use App\Entity\Scenario\ScenarioTemplate;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ScenarioTemplateCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ScenarioTemplate::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideWhenCreating()
                ->hideWhenUpdating(),
            TextField::new('name'),
            IntegerField::new('projectId'),
            ArrayField::new('scenario')
                ->hideWhenUpdating(),
        ];
    }

    public function configureResponseParameters(KeyValueStore $responseParameters): KeyValueStore
    {
        return $responseParameters;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof ScenarioTemplate) return;

//        dd($entityInstance);
        // {"name":"/command1","type":"command","content":{"message":"Что делаешь?","replyMarkup":[{"text":"Ничего"},{"text":"Что-то"}]},"actionAfter":[{"type":"message","value":{"text":"Дополнительное сообщение"}},{"type":"message","value":{"text":"Дополнительное сообщение"}}],"sub":[{"name":"Хорошо","type":"message","content":{"message":"Хорошо что всё хорошо"}},{"name":"Плохо","type":"message","content":{"message":"Плохо что всё плохо"}}]}

        parent::persistEntity($entityManager, $entityInstance);
    }
}
