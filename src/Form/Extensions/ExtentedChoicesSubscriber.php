<?php
//! Not in use
//* Not needed, this is an extension for tags JSON version (Form\DocumentsType)

namespace App\Form\Extensions;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use App\Form\ExtentedChoiceType;

class ExtentedChoicesSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => ['populateChoices', -50],
            FormEvents::PRE_SUBMIT => ['populateChoices', -50],
        ];
    }

    public function populateChoices(FormEvent $event)
    {
        foreach ($event->getForm()->all() as $child) {
            if (
                get_class(
                    $child
                        ->getConfig()
                        ->getType()
                        ->getInnerType()
                ) === ExtentedChoiceType::class
            ) {
                $this->populateChoice($event, $child->getName());
            }
        }
    }

    private function populateChoice(FormEvent $event, $childName)
    {
        $form = $event->getForm();
        $child = $form->get($childName);
        $type = get_class(
            $child
                ->getConfig()
                ->getType()
                ->getInnerType()
        );
        $options = $child->getConfig()->getOptions();

        $choices = [];

        $data = $event->getData();

        if (!array_key_exists($childName, $data)) {
            return;
        }
        $data = $data[$childName];

        if ($data != null) {
            if (is_array($data)) {
                foreach ($data as $choice) {
                    $choices[$choice] = $choice;
                }
            } else {
                $choices[$data] = $data;
            }
        }

        // Feel free to find a better way to reuse the defined options. In Sf 2.6 it was not possible here :
        $newOptions = [
            'route' => $options['route'],
            'required' => $options['required'],
            'multiple' => $options['multiple'],
            'choices' => $choices,
        ];
        $form->add($childName, $type, $newOptions);
    }
}
