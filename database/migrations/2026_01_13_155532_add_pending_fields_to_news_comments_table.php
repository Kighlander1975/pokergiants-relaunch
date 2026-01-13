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
        Schema::table('news_comments', function (Blueprint $table) {
            $table->text('pending_content')->nullable()->after('content');
            $table->string('pending_author_display')->nullable()->after('pending_content');
            $table->foreignId('pending_user_id')->nullable()->after('pending_author_display')->constrained('users')->nullOnDelete();
            $table->timestamp('pending_at')->nullable()->after('pending_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news_comments', function (Blueprint $table) {
            $table->dropForeign(['pending_user_id']);
            $table->dropColumn(['pending_content', 'pending_author_display', 'pending_user_id', 'pending_at']);
        });
    }
};
