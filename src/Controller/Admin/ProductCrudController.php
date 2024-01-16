<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInSingular('Products')
            ->setEntityLabelInPlural('Products')
            ->setSearchFields(['seller', 'sku', 'price', 'name', 'reviews_count'])
            ->setDefaultSort(['created_date' => 'DESC'])
            ->setDefaultSort(['updated_date' => 'DESC'])
            ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('seller'))
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('seller');
        yield NumberField::new('sku');
        yield NumberField::new('price');
        yield NumberField::new('reviews_count');
        yield TextField::new('name');
        yield DateTimeField::new('created_date')->hideOnForm();
        yield DateTimeField::new('updated_date  ')->hideOnForm();
    }
}
