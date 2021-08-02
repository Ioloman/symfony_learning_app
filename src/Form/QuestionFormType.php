<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
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

        if ($options['include_asked_at']) {
            $builder->add(
                'askedAt',
                null,
                ['widget' => 'single_text']
            );
        }

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function(FormEvent $event) {
                /** @var Question|null $data */
                $data = $event->getData();
                if (!$data) {
                    return;
                }

                $this->setupSpecificTopicField(
                    $event->getForm(),
                    $data->getTopic()
                );
            }
        );

        $builder->get('topic')->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) {
                $form = $event->getForm();
                $this->setupSpecificTopicField(
                    $form->getParent(),
                    $form->getData()
                );
            }
        );
    }

    private function setupSpecificTopicField(FormInterface $form, ?string $topic)
    {
        if ($topic === null) {
            $form->remove('specificTopic');

            return;
        }

        $subtopics = $this->getSpecificTopic($topic);

        if ($subtopics === null) {
            $form->remove('specificTopic');

            return;
        }

        $form->add('specificTopic', ChoiceType::class, [
            'choices' => $subtopics,
            'required' => false,
            'placeholder' => 'Choose a subtopic'
        ]);
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

        return $subtopics[$topic] ?? null;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
            'include_asked_at' => true,
        ]);
    }
}
