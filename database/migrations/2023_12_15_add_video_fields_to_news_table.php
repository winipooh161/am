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
        Schema::table('news', function (Blueprint $table) {
            $table->text('video_iframe')->nullable()->after('content');
            $table->string('video_author_name')->nullable()->after('video_iframe');
            $table->string('video_author_link')->nullable()->after('video_author_name');
            $table->text('video_tags')->nullable()->after('video_author_link');
            $table->string('video_title')->nullable()->after('video_tags');
            $table->text('video_description')->nullable()->after('video_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn([
                'video_iframe',
                'video_author_name',
                'video_author_link',
                'video_tags',
                'video_title',
                'video_description',
            ]);
        });
    }
};
