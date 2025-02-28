<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BookCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Book::class;
    }

 
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('userPro'),
            TextField::new('isbn'),
            TextField::new('title'),  
            IntegerField::new('parutionAt', 'Année de parution')
            ->setFormTypeOptions([
                'attr' => ['min' => 1650, 'max' => date('Y')]
            ]),
            TextField::new('publisher'),    
            TextareaField::new('resum'),
            TextField::new('price'),
            AssociationField::new('categorie'),
            AssociationField::new('author'),
        ];
    }
    
}
