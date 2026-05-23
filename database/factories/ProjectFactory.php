<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'slug' => Str::slug($name).'-'.fake()->unique()->numerify('####'),
            'name' => $name,
            'content' => [
                'title' => $name,
                'database' => 'postgresql',
                'tables' => [],
                'relationships' => [],
                'notes' => [],
                'subjectAreas' => [],
                'transform' => [
                    'pan' => ['x' => 0, 'y' => 0],
                    'zoom' => 1,
                ],
            ],
        ];
    }
}
