<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    private const int PASSWORD_MIN_LENGTH = 6;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', null, [
                'label' => 'register.first_name.label',
                'attr' => ['placeholder' => 'register.first_name.placeholder'],
            ])
            ->add('lastName', null, [
                'label' => 'register.last_name.label',
                'attr' => ['placeholder' => 'register.last_name.placeholder'],
            ])
            ->add('email', null, [
                'label' => 'register.email.label',
                'attr' => ['placeholder' => 'register.email.placeholder'],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'register.password.label',
                'attr' => [
                    'placeholder' => 'register.password.placeholder',
                    'autocomplete' => 'new-password',
                    'minlength' => self::PASSWORD_MIN_LENGTH,
                ],
                'help' => 'register.password.help',
                'help_translation_parameters' => ['{{ min_length }}' => self::PASSWORD_MIN_LENGTH],
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => self::PASSWORD_MIN_LENGTH,
                        'minMessage' => 'register.password.short',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'register.agree_terms',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'register.terms.required',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
