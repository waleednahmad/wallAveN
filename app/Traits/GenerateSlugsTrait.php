<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait GenerateSlugsTrait
{
    public function generateUniqueSlug($object, $title_en,  $col_name = 'slug')
    {
        $slug = trim(strtolower(str_replace(array(' ', '"', '>', '<', '#', '%', '|', '/'), '-', trim($title_en))));

        if ($object) {
            // Check if the current slug is the same as the existing one
            if ($object->$col_name === $slug) {
                return $slug;
            }

            $count = 0;
            $query = $object::where($col_name, $slug)->where('id', '!=', $object->id);

            $query->first();

            if ($query && $query->count() > 0) {
                $max = $query->count();
                if (isset($max) && is_numeric($max)) {
                    $count = $max + 1;
                }
            }
            return $this->slugChecker($object, $slug, $count, $col_name);
        }
    }

    public function slugChecker($object, $slug, $count, $col_name)
    {
        if ($count > 0) {
            $new_slug = $slug . '-' . $count;
            $query = $object::where($col_name, $new_slug)->first();
            if ($query) {
                $count++;
                return $this->slugChecker($object, $slug, $count, $col_name);
            } else {
                return $new_slug;
            }
        } else {
            return $slug;
        }
    }
}
