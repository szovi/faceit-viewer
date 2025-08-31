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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes()->after('remember_token');
            }
        });

        Schema::table('elo_history', function (Blueprint $table) {
            if (!Schema::hasColumn('elo_history', 'deleted_at')) {
                $table->softDeletes()->after('recorded_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('elo_history', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
