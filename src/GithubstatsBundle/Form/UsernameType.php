<?php

namespace GithubstatsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;


class UsernameType extends AbstractType
{
    public function __construct(){}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('user_name',TextType::class , array(
            'label'=>'Github user name:',
            'required' => true
        ));
    }

    public function getName()
    {
        return 'github_form';
    }
}
