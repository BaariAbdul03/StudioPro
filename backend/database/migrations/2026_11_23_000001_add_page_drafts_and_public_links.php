<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->jsonb('project_data')->nullable();
            $table->uuid('public_id')->nullable()->unique();
        });

        Schema::table('page_versions', function (Blueprint $table) {
            $table->jsonb('project_data')->nullable();
        });

        // Previous saves set this flag without creating a public release.
        DB::table('pages')->where('is_published', true)->update(['is_published' => false]);
    }

    public function down(): void
    {
        Schema::table('page_versions', function (Blueprint $table) {
            $table->dropColumn('project_data');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropUnique(['public_id']);
            $table->dropColumn(['project_data', 'public_id']);
        });
    }
};
