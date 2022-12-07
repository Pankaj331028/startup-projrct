<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('role_id')->nullable()->after('id');
            $table->string('first_name')->nullable()->comment('given name of the user')->after('name');
            $table->string('last_name')->nullable()->comment('surname of the user')->after('first_name');

            $table->after('password', function ($table) {
                $table->integer('is_active')->default('1')->comment('1[Active] 2[Inactive] 3[Deleted]');
                $table->string('status')->nullable();
                $table->unsignedBigInteger('createdby_userid')->nullable();
                $table->unsignedBigInteger('updatedby_userid')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role_id',
                'first_name',
                'last_name',
                'is_active',
                'status',
                'createdby_userid',
                'updatedby_userid',
            ]);
        });
    }
};
