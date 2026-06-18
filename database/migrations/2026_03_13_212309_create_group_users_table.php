<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('group_users', function (Blueprint $table) {
            $table->id('group_user_id');
            $table->string('group_user_name');
            $table->boolean('is_super_user')->default(false);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('group_user_id')->nullable();
            $table->boolean('is_enable')->default(true);

            $table->foreign('group_user_id')
                ->references('group_user_id')
                ->on('group_users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'group_user_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['group_user_id']);
                $table->dropColumn('group_user_id');
            });
        }

        if (Schema::hasColumn('users', 'is_enable')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_enable');
            });
        }

        Schema::dropIfExists('group_users');
    }
};
