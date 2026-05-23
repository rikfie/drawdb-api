<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectApiTest extends TestCase
{
    use RefreshDatabase;

    private function sampleContent(array $overrides = []): array
    {
        return array_merge([
            'title' => 'My database design',
            'database' => 'postgresql',
            'tables' => [],
            'relationships' => [],
            'notes' => [],
            'subjectAreas' => [],
            'transform' => [
                'pan' => ['x' => 0, 'y' => 0],
                'zoom' => 1,
            ],
        ], $overrides);
    }

    public function test_list_projects_returns_bare_array_with_camel_case_keys(): void
    {
        Project::factory()->create([
            'slug' => 'shop-schema',
            'name' => 'Shop schema',
            'content' => $this->sampleContent([
                'title' => 'Shop schema',
                'database' => 'mysql',
            ]),
        ]);

        $response = $this->getJson('/api/projects');

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment([
                'slug' => 'shop-schema',
                'name' => 'Shop schema',
                'database' => 'mysql',
            ])
            ->assertJsonStructure([
                '*' => ['slug', 'name', 'updatedAt', 'database'],
            ]);
    }

    public function test_create_project_returns_slug_and_generates_unique_slugs(): void
    {
        $content = $this->sampleContent();

        $first = $this->postJson('/api/projects', [
            'name' => 'My database design',
            'content' => $content,
        ]);

        $first->assertCreated()
            ->assertExactJson(['slug' => 'my-database-design']);

        $second = $this->postJson('/api/projects', [
            'name' => 'My database design',
            'content' => $content,
        ]);

        $second->assertCreated()
            ->assertExactJson(['slug' => 'my-database-design-2']);
    }

    public function test_show_project_returns_diagram_json_at_top_level(): void
    {
        $content = $this->sampleContent(['title' => 'Loaded project']);

        Project::factory()->create([
            'slug' => 'loaded-project',
            'name' => 'Loaded project',
            'content' => $content,
        ]);

        $response = $this->getJson('/api/projects/loaded-project');

        $response->assertOk()
            ->assertExactJson($content);
    }

    public function test_update_project_replaces_content_and_syncs_name_from_title(): void
    {
        $project = Project::factory()->create([
            'slug' => 'existing-project',
            'name' => 'Old name',
            'content' => $this->sampleContent(['title' => 'Old name']),
        ]);

        $updatedContent = $this->sampleContent([
            'title' => 'Renamed project',
            'database' => 'sqlite',
        ]);

        $response = $this->putJson('/api/projects/existing-project', $updatedContent);

        $response->assertOk()
            ->assertExactJson($updatedContent);

        $project->refresh();

        $this->assertSame('Renamed project', $project->name);
        $this->assertSame($updatedContent, $project->content);
    }

    public function test_delete_project_returns_no_content(): void
    {
        Project::factory()->create(['slug' => 'delete-me']);

        $response = $this->deleteJson('/api/projects/delete-me');

        $response->assertNoContent();

        $this->assertDatabaseMissing('projects', ['slug' => 'delete-me']);
    }

    public function test_unknown_project_returns_not_found_message(): void
    {
        $response = $this->getJson('/api/projects/does-not-exist');

        $response->assertNotFound()
            ->assertExactJson(['message' => 'Project not found']);
    }

    public function test_create_project_validation_errors(): void
    {
        $response = $this->postJson('/api/projects', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'content']);
    }
}
