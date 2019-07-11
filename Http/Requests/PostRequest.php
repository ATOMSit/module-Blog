<?php

namespace Modules\Blog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        switch ($this->method()) {
            case 'DELETE':
                {
                    return true;
                }
            case 'POST':
                {
                    return true;
                }
            case 'PUT':
                {
                    if (Auth::user()->hasPermissionTo('post_update', 'blog')) {
                        return true;
                    } else {
                        return true;
                    }
                }
            default:
                break;
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
                {
                    return [
                        'validation' => [
                            'accepted'
                        ]
                    ];
                }
            case 'POST':
                {
                    return [
                        'title' => [
                            'required',
                            'string',
                            'min:3',
                            'max:250'
                        ],
                        'body' => [
                            'required',
                            'string',
                            'min:3',
                            'max:250000'
                        ],
                        'online' => [
                            'required',
                        ],
                        'indexable' => [
                            'required',
                        ],
                        'published_at' => [
                            'required',
                        ],
                        'unpublished_at' => [
                            'nullable',
                        ],
                    ];
                }
            case 'PUT':
                {
                    return [
                        'title' => [
                            'required',
                            'string',
                            'min:3',
                            'max:250'
                        ],
                        'body' => [
                            'required',
                            'string',
                            'min:3',
                            'max:250000'
                        ],
                        'online' => [
                            'required',
                        ],
                        'indexable' => [
                            'required',
                        ],
                        'published_at' => [
                            'required',
                        ],
                        'unpublished_at' => [
                            'nullable',
                        ],
                    ];
                }
            case 'PATCH':
                {
                    return [];
                }
            default:
                break;
        }
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => trans('blog::post_translation.fields.title.required'),
            'title.string' => trans('blog::post_translation.fields.title.string'),
            'title.min' => trans('blog::post_translation.fields.title.min'),
            'title.max' => trans('blog::post_translation.fields.title.max'),
            'body.required' => trans('blog::post_translation.fields.body.required'),
            'body.string' => trans('blog::post_translation.fields.body.string'),
            'body.min' => trans('blog::post_translation.fields.body.min'),
            'body.max' => trans('blog::post_translation.fields.body.max'),
            'status.required' => trans('blog::post_translation.fields.status.required'),
            'status.string' => trans('blog::post_translation.fields.status.string'),
            'status.in' => trans('blog::post_translation.fields.status.in'),
            'indexable.required' => trans('blog::post_translation.fields.indexable.required'),
            'indexable.boolean' => trans('blog::post_translation.fields.indexable.boolean'),
            'published_at_date.required' => trans('blog::post_translation.fields.published_at_date.required'),
            'published_at_date.date' => trans('blog::post_translation.fields.published_at_date.date'),
            'published_at_time.required' => trans('blog::post_translation.fields.published_at_time.required'),
            'published_at_time.date_format' => trans('blog::post_translation.fields.published_at_time.date_format'),
            'unpublished_at_date.nullable' => trans('blog::post_translation.fields.unpublished_at_time.nullable'),
            'unpublished_at_date.date' => trans('blog::post_translation.fields.unpublished_at_time.date'),
            'unpublished_at_time.nullable' => trans('blog::post_translation.fields.unpublished_at_time.nullable'),
            'unpublished_at_time.date_format' => trans('blog::post_translation.fields.unpublished_at_time.date_format'),
        ];
    }
}
