<?php

namespace App\Support;

use App\Models\Project;
use Illuminate\Support\Str;

class ProjectSlug
{
    public static function uniqueFromName(string $name): string
    {
        $base = Str::slug($name) ?: 'project';
        $slug = $base;
        $n = 2;

        while (Project::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$n;
            $n++;
        }

        return $slug;
    }
}
