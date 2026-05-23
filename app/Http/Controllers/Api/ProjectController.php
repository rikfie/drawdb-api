<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectSummaryResource;
use App\Models\Project;
use App\Support\ProjectSlug;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    public function index(): JsonResponse
    {
        $projects = Project::query()
            ->orderByDesc('updated_at')
            ->get();

        return response()->json(
            ProjectSummaryResource::collection($projects)->resolve()
        );
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        $name = $request->validated('name');
        $content = $request->input('content');

        $project = Project::create([
            'slug' => ProjectSlug::uniqueFromName($name),
            'name' => $name,
            'content' => $content,
        ]);

        return response()->json(['slug' => $project->slug], 201);
    }

    public function show(Project $project): JsonResponse
    {
        return response()->json($project->content);
    }

    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        $content = $request->all();

        $project->content = $content;

        if (isset($content['title'])) {
            $project->name = $content['title'];
        }

        $project->save();

        return response()->json($project->content);
    }

    public function destroy(Project $project): Response
    {
        $project->delete();

        return response()->noContent();
    }
}
