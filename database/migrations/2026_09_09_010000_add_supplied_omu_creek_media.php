<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('projects') || ! Schema::hasTable('project_media')) {
            return;
        }

        $projectId = DB::table('projects')->where('slug', 'omu-creek')->value('id');

        if (! $projectId) {
            return;
        }

        $media = [
            [
                'role' => 'hero_image',
                'path' => 'assets/images/omu-creek.png',
                'alt_text' => 'Aerial view of bridge construction over the Omu Creek waterway.',
                'sort_order' => 0,
            ],
            [
                'role' => 'gallery_image',
                'path' => 'assets/images/omu-creek-2.png',
                'alt_text' => 'Closer aerial view of bridge construction over the Omu Creek waterway.',
                'sort_order' => 1,
            ],
        ];

        foreach ($media as $item) {
            $roleAlreadyManaged = DB::table('project_media')
                ->where('project_id', $projectId)
                ->where('role', $item['role'])
                ->exists();

            if ($roleAlreadyManaged) {
                continue;
            }

            DB::table('project_media')->insert($item + [
                'project_id' => $projectId,
                'kind' => 'image',
                'disk' => 'public',
                'external_url' => null,
                'mime_type' => 'image/png',
                'caption' => null,
                'credit' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('projects') || ! Schema::hasTable('project_media')) {
            return;
        }

        $projectId = DB::table('projects')->where('slug', 'omu-creek')->value('id');

        if (! $projectId) {
            return;
        }

        DB::table('project_media')
            ->where('project_id', $projectId)
            ->whereIn('path', [
                'assets/images/omu-creek.png',
                'assets/images/omu-creek-2.png',
            ])
            ->delete();
    }
};
