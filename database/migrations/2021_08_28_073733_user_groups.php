<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class UserGroups
 */
class UserGroups extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_memberships');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('user_groups');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * user is a member of a user_group through a user_group_role
         * may have multiple roles in a group
         */
        Schema::create(
            'user_groups', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();

            $table->string('title', 255);
            $table->unique('title');
        }
        );

        Schema::create(
            'user_roles', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();

            $table->string('title', 255);
            $table->unique('title');
        }
        );

        Schema::create(
            'group_memberships',
            static function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->softDeletes();
                $table->integer('user_id', false, true);
                $table->bigInteger('user_group_id', false, true);
                $table->bigInteger('user_role_id', false, true);

                $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('user_group_id')->references('id')->on('user_groups')->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('user_role_id')->references('id')->on('user_roles')->onUpdate('cascade')->onDelete('cascade');
                $table->unique(['user_id', 'user_group_id', 'user_role_id']);
            }
        );

    }
}