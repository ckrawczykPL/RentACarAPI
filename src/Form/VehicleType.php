<?php

namespace App\Form;

use App\Entity\Vehicle;
use App\Entity\Brand;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VehicleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('brand', EntityType::class, [
                'class' => Brand::class,
                'choice_label' => 'name',
                'label' => 'Brand',
                'required' => true,
            ])
            ->add('registrationNumber', TextType::class, [
                'label' => 'Registration number',
                'required' => true,
            ])
            ->add('vin', TextType::class, [
                'label' => 'VIN',
                'required' => true,
            ])
            ->add('clientEmail', EmailType::class, [
                'label' => 'Client email',
                'required' => true,
            ])
            ->add('clientAddress', TextareaType::class, [
                'label' => 'Client address',
                'required' => true,
            ])
            ->add('isCurrentlyRented', CheckboxType::class, [
                'label' => 'Is Currently rented',
                'required' => false,
            ])
            ->add('currentLocationAddress', TextareaType::class, [
                'label' => 'Current location address',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
