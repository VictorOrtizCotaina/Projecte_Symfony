<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'attr' => array(
                )
            ))
            ->add('email', EmailType::class, array(
                'attr' => array(
                )
            ))
            ->add('subject', TextType::class, array(
                'attr' => array(
                )
            ))
            ->add('text', TextareaType::class, array(
                'attr' => array(
                    'cols' => 90,
                    'rows' => 10,
                )
            ))
            ->add('save', SubmitType::class, array('label' => 'Enviar'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}
