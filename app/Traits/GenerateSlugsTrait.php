<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait GenerateSlugsTrait
{
    public function generateUniqueSlug($model, $title_en,  $col_name = 'slug')
    {
        // Generate slug from title
        $slug = trim(strtolower(str_replace(array(' ', '"', '>', '<', '#', '%', '|', '/'), '-', trim($title_en))));
        // Remove duplicate hyphens
        $slug = preg_replace('/-+/', '-', $slug);

        if ($model) {
            // Check if the current slug is the same as the existing one
            if ($model->$col_name === $slug) {
                return $slug;
            }

            $count = 0;
            $query = $model::where($col_name, $slug)->where('id', '!=', $model->id);

            $query->first();

            if ($query && $query->count() > 0) {
                $max = $query->count();
                if (isset($max) && is_numeric($max)) {
                    $count = $max + 1;
                }
            }
            return $this->slugChecker($model, $slug, $count, $col_name);
        }
    }

    public function slugChecker($model, $slug, $count, $col_name)
    {
        if ($count > 0) {
            $new_slug = $slug . '-' . $count;
            $query = $model::where($col_name, $new_slug)->first();
            if ($query) {
                $count++;
                return $this->slugChecker($model, $slug, $count, $col_name);
            } else {
                return $new_slug;
            }
        } else {
            return $slug;
        }
    }
}
