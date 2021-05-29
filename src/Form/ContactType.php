<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\Entity\Contact;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nom', TextType::class, ["constraints" => [ 
                           new NotBlank(["message" => "Veuillez remplir ce champ"]),
                           new Length([ "min" => 2, "max" => 20, "minMessage" => "Le titre doit avoir au moins 2 caractères", "maxMessage" =>"Le titre ne doit pas dépasser 20 caractères" ])
                         ]
       ])
        ->add('email', EmailType::class,["constraints" => [ 
            new NotBlank(["message" => "Veuillez remplir ce champ"]),
            new Length([ "min" => 2, "max" => 20, "minMessage" => "Le titre doit avoir au moins 2 caractères", "maxMessage" =>"Le titre ne doit pas dépasser 20 caractères" ])
            ]
            ])
        ->add('objet', TextType::class,["constraints" => [ 
            new NotBlank(["message" => "Veuillez remplir ce champ"]),
            new Length([ "min" => 2, "max" => 20, "minMessage" => "Le titre doit avoir au moins 2 caractères", "maxMessage" =>"Le titre ne doit pas dépasser 20 caractères" ])
          ]
          ])
        ->add('msg',TextareaType::class,["constraints" => [ 
            new NotBlank(["message" => "Veuillez remplir ce champ"]),
            new Length([ "min" => 2, "max" => 300, "minMessage" => "Le titre doit avoir au moins 2 caractères", "maxMessage" =>"Le titre ne doit pas dépasser 300 caractères" ])
          ]
          ])
        ->add('paiement', SubmitType::class)
    ;}

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
