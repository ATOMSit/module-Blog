<?php

namespace Modules\Blog\Forms;

use App\Forms\PictureForm;
use Carbon\Carbon;
use Kris\LaravelFormBuilder\Field;
use Kris\LaravelFormBuilder\Form;
use Modules\SEOBasic\Forms\BasicForm;

class PostForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('title', Field::TEXT, [
                'template' => 'application.layouts.fields.text',
                'description' => "",
                'rules' => 'required|min:3|max:250'
            ])
            ->add('body', Field::TEXTAREA, [
                'rules' => 'required|min:3|max:250000'
            ])
            ->add('online', Field::SELECT, [
                'choices' => [1 => 'Oui', 0 => 'Non'],
                'template' => 'application.layouts.fields.select',
                'rules' => 'required'
            ])
            ->add('indexable', Field::SELECT, [
                'choices' => [1 => 'Oui', 0 => 'Non'],
                'template' => 'application.layouts.fields.select',
                'rules' => 'required'
            ])
            ->add('published_at', Field::TEXT, [
                'value' => Carbon::now()->format('d/m/Y H:i'),
                'template' => 'application.layouts.fields.text',
                'rules' => 'required'
            ])
            ->add('unpublished_at', Field::TEXT, [
                'template' => 'application.layouts.fields.text',
                'rules' => 'nullable'
            ])
            ->add('input_cropper', Field::FILE, [
            ])
            ->add('picture', 'form', [
                'class' => $this->formBuilder->create(PictureForm::class)
            ])
            ->add('seobasic', 'form', [
                'class' => $this->formBuilder->create(BasicForm::class)
            ])
            ->add('submit', Field::BUTTON_SUBMIT);
    }
}
