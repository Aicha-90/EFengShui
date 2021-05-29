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
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormView;


class UserSubsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', Type\TextType::class, ["label"=>"Name","attr"=> ["placeholder" => "Enter your name"]])
            ->add('prenom', Type\TextType::class, ["label"=>"First name","attr"=> ["placeholder" => "Enter your first-name"]])
            ->add('email', Type\TextType::class, ["label"=>"Email","attr"=> ["placeholder" => "Enter your email"]])
            ->add('password', PasswordType::class, ["label"=>"Password","help"=>"Your password must have minimum 6 caracters","attr"=>["placeholder" => "Enter your password"],
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Enter your password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password must have minimum {{ limit }} caracters',
                        'max' => 4096,
                    ]),
                ],
            ])

            ->add('conditions', CheckboxType::class, [ "label"=>"I accept de legal conditions",
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        
                        'message' => "You have to confirm the conditions legal to subscribe",
                    ]),
                ],
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