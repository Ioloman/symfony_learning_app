<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionFormType extends AbstractType
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                null,
                ['help' => 'Enter your magnificent question']
            )
            ->add('question')
            ->add(
                'askedAt',
                null,
                ['widget' => 'single_text']
            )
            ->add(
                'author',
                null,
                [
                    'choice_label' => function (User $user) {
                        return sprintf('%s (%s)', $user->getFirstName(), $user->getEmail());
                    },
                    'placeholder' => 'Choose an author',
                    'choices' => $this->userRepository->findAllEmailsAlphabetically(),
                    'invalid_message' => 'Get a life bruh'
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
