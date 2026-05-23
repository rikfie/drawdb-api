<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Project */
class ProjectSummaryResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $database = $this->content['database'] ?? null;

        return array_filter([
            'slug' => $this->slug,
            'name' => $this->name,
            'updatedAt' => $this->updated_at?->utc()->format('Y-m-d\TH:i:s.u\Z'),
            'database' => $database,
        ], fn ($value) => $value !== null);
    }
}
