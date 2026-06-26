<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cms_collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->json('fields'); // [{name: 'Body', type: 'rich-text', key: 'body'}]
            $table->timestamps();
            $table->unique(['project_id', 'slug']);
        });

        Schema::create('cms_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained('cms_collections')->cascadeOnDelete();
            $table->string('slug');
            $table->json('data'); // key-value content mapping fields
            $table->string('status', 50)->default('draft');
            $table->timestamps();
            $table->unique(['collection_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_items');
        Schema::dropIfExists('cms_collections');
    }
};
