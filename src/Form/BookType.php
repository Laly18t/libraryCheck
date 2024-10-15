<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\BookCollection;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('author')
            ->add('description')
            ->add('thumbnail')
            ->add('ISBN')
            ->add('category_id', EntityType::class, [
                'class' => BookCategory::class,
                'choice_label' => 'id',
            ])
            ->add('collection_id', EntityType::class, [
                'class' => BookCollection::class,
                'choice_label' => 'id',
            ])
            // ->add('users', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            //     'multiple' => true,
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
