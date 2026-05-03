<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_roles', function (Blueprint $table) {
            $table->string('model_type', 255)->default('App\\Models\\User')->after('id');
            $table->unsignedBigInteger('model_id')->nullable()->after('model_type');
            $table->foreign('model_id')->references('id')->on('users')->nullOnDelete();
        });

        // Back-fill model_id from user_id for any existing rows
        DB::statement('UPDATE user_roles SET model_id = user_id WHERE model_id IS NULL AND user_id IS NOT NULL');
    }

    public function down(): void
    {
        Schema::table('user_roles', function (Blueprint $table) {
            $table->dropForeign(['model_id']);
            $table->dropColumn(['model_type', 'model_id']);
        });
    }
};
