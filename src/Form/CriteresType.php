<?php

namespace App\Form;

use App\Entity\Critere;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\User;
//Permet d'utiliser la class Type et d'ajouter automatiquement les types dont j'ai besoin. (Comme pour le Type\TextType::class plus bas)
use Symfony\Component\Validator\Constraints;
//Permet d'utiliser les contraintes



class CriteresType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre_diagnostic', Type\TextType::class, ["attr"=> ["placeholder" => "Veuillez entrer le titre de votre diagnostic."], "label" => "Titre de votre diagnostic"])
            ->add('nb_m_carre', Type\TextType::class, ["attr"=> ["placeholder" => "En m²."], "label" => "Combien de surface possédez-vous?"])
            ->add('lieu', Type\ChoiceType::class, ["attr"=>['class' => 'choixLieu'], 'choices' => ['Bureau' => 'bureau', 'Domicile' => 'domicile'], "label" => false,
            'expanded' => true,
            'multiple' => false])
            ->add('annee_constr', Type\TextType::class, ["attr"=> ["placeholder" => "_ _ _ _"], "label" => "Quelle est l'année de construction de votre immeuble?"])
            ->add('plan_lieu', Type\FileType::class, ["mapped" => false ], ["attr"=> ["label" => "Chargez le plan de votre lieu."]])
            ->add('photo_lieu', Type\FileType::class, ["mapped" => false ], ["attr"=> ["label" => "Chargez les photos de vos pièces."]])
            ->add('orientation', Type\ChoiceType::class, ['choices' => [ "Nord" => "nord", "Nord-Ouest" => "nord_ouest", "Nord-Est" => "nord_est", "Est" => "est", "Sud" => "sud", "Sud-Ouest" => "sud_ouest", "Sud-Est" => "sud_est", "Ouest" => "ouest"]] , ["label" => "Veuillez sélectionner l'orientation de votre immeuble."])
            ->add('suivant', Type\SubmitType::class)

        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Critere::class,
        ]);
    }
}
