<?php

namespace App\Actions\Projects;

use App\Models\Project;

final readonly class ProjectImportResult
{
    public function __construct(
        public Project $project,
        public bool $imported,
    ) {}
}
