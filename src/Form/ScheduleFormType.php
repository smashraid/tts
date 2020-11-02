<?php

namespace App\Form;

use App\Entity\Schedule;
use App\Entity\Employee;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ScheduleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start', DateType::class, [                
                'label' => 'Fecha',
                'widget' => 'choice',
                'years' => range(date('Y'), date('Y')+2),
                //'months' => range(date('m'), 12),
                //'days' => range(date('d'), 31),
                'data' => new \DateTime(),
            ])
            ->add('slot', TextType::class, [
                'label' => 'Hora',
            ])
            ->add('link', TextType::class, [
                'label' => 'Url',
            ])            
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'name',
                'label' => 'Usuario',
                //'placeholder' => 'Choose an option',                
            ])
            ->add('employee', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => 'name',
                'label' => 'Especialista',
                //'placeholder' => 'Choose an option',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Schedule::class,
            'user_id' => 0
        ]);

        $resolver->setAllowedTypes('user_id', 'int');
    }
}
