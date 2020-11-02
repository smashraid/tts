<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, [
                'constraints' => [                    
                    new Email([
                        'message' => 'El correo {{ value }} no es valido',
                    ]),
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Debe aceptar los terminos y condiciones',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor ingrese su contraseña',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'La contraseña debe tener al menos {{ limit }} caracteres',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('name', TextType::class, [
                'attr' => ['maxlength' => '100'],
                'constraints' => [                    
                    new NotBlank([
                        'message' => 'Por favor ingrese su nombre',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Nombre no valido',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ]
            ])
            ->add('lastname', TextType::class, [
                'attr' => ['maxlength' => '100'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor ingrese su apellido',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Apellido no valido',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ]
            ])
            ->add('phone', TextType::class, [
                'attr' => ['maxlength' => '9'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor ingrese su numero de telefono',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Telefono no valido',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
