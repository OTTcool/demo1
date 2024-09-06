<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

final class UserAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form->add('email', EmailType::class, [
            'label' => 'Gmail',
            'required' => true,
        ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'required' => true,
            ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('email', null, [
            'label' => 'Gmail',
        ]);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('email', null, [
            'label' => 'Gmail',
        ])
            ->add('password', 'text', [
                'label' => 'Password', // Note: Passwords should generally not be listed for security reasons
                'template' => 'custom_template_to_hide_password.html.twig', // Optional: You might want to mask the password here
            ])
            ->add(ListMapper::NAME_ACTIONS, null, [
                'label' => 'Actions',
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ]
            ]);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('email', null, [
            'label' => 'Gmail',
        ])
            ->add('password', 'text', [
                'label' => 'Password',
            ]);
    }
}
