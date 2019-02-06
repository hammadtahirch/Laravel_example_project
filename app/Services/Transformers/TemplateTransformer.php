<?php

namespace App\Services\Transformers;

use App\Models\Eloquent\Collection;
use App\Models\Eloquent\EmailTemplate;
use App\Models\Eloquent\Shop;
use App\Models\Eloquent\User;
use League\Fractal\TransformerAbstract;

class TemplateTransformer extends TransformerAbstract
{
    /*
    |--------------------------------------------------------------------------
    | Template Transformer
    |--------------------------------------------------------------------------
    |
    | This transformer is responsible to Email Template Transformation
    |
    */

    /**
     * Create a new transformer instance.
     *
     * @param EmailTemplate $template
     * @return array
     */
    public function transform(EmailTemplate $template)
    {

        return [
            'id' => $template->id,
            'key' => $template->key,
            'subject' => $template->subject,
            'from_email' => $template->from_email,
            'from_name' => $template->from_name,
            'email_body' => $template->email_body,
            'merge_field' => $template->merge_field,
            'is_enabled' => $template->is_enabled,

            "created_by" => $template->created_by,
            "updated_by" => $template->updated_by,
            "deleted_at" => $template->deleted_at,
            "created_at" => $template->created_at,
            "updated_at" => $template->updated_at
        ];
    }
}