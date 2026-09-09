<?php

namespace App\Support;

use App\Models\Project;
use Illuminate\Http\Request;

final class ProjectPageResolver
{
    public function __construct(private readonly SelotemnaContent $content) {}

    /** @return array<string, mixed> */
    public function resolve(Request $request, string $slug): array
    {
        if (! $request->filled('preview')) {
            return $this->content->project($slug);
        }

        abort_unless($request->hasValidSignature(), 403);
        abort_unless($request->user()?->is_admin === true, 403);

        $project = Project::query()
            ->whereKey($request->integer('preview'))
            ->where('slug', $slug)
            ->firstOrFail();

        return $this->content->projectPreview($project);
    }
}
