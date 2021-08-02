<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
        /** @var Question|null $question */
        $question = $options['data'] ?? null;
        $isEdit = $question && $question->getId();
        $topic = $question?->getTopic();

        $builder
            ->add(
                'name',
                null,
                ['help' => 'Enter your magnificent question']
            )
            ->add('question')
            ->add(
                'author',
                null,
                [
                    'disabled' => $isEdit,
                    'choice_label' => function (User $user) {
                        return sprintf('%s (%s)', $user->getFirstName(), $user->getEmail());
                    },
                    'placeholder' => 'Choose an author',
                    'choices' => $this->userRepository->findAllEmailsAlphabetically(),
                    'invalid_message' => 'Get a life bruh'
                ]
            )
            ->add('topic', ChoiceType::class, [
                'choices' => [
                    'Magic Questions' => 'magic',
                    'Muggles Questions' => 'muggles',
                    'Potion Making Questions' => 'potions'
                ],
                'required' => false,
                'placeholder' => 'Choose a topic'
            ])
        ;
        $subtopics = $topic ? $this->getSpecificTopic($topic) : null;
        if ($topic && $subtopics) {
            $builder->add('specificTopic', ChoiceType::class, [
                'choices' => $subtopics,
                'required' => false,
                'placeholder' => 'Choose a subtopic'
            ]);
        }

        if ($options['include_asked_at']) {
            $builder->add(
                'askedAt',
                null,
                ['widget' => 'single_text']
            );
        }
    }

    private function getSpecificTopic(?string $topic)
    {
        $magic = [
            'attac ⚔',
            'protec 🛡',
            'hec 😻'
        ];

        $muggles = [
            'vehicles 🚙',
            'devices 📱',
            'mindset 🤯'
        ];

        $subtopics = [
            'magic' => array_combine($magic, $magic),
            'muggles' => array_combine($muggles, $muggles),
            'potions' => null
        ];

        return $subtopics[$topic];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
            'include_asked_at' => true,
        ]);
    }
}
