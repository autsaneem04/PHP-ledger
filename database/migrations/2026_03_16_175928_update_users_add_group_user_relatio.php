<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // เพิ่ม column ถ้ายังไม่มี
            if (!Schema::hasColumn('users', 'group_user_id')) {

                $table->foreignId('group_user_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('group_users', 'group_user_id')
                    ->nullOnDelete();

            }

            if (!Schema::hasColumn('users', 'is_enable')) {
                $table->boolean('is_enable')->default(true);
            }

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign(['group_user_id']);
            $table->dropColumn(['group_user_id','is_enable']);

        });
    }
};
