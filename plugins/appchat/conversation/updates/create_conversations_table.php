<?php namespace AppChat\Conversation\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateConversationsTable Migration
 *
 * @link https://docs.octobercms.com/3.x/extend/database/structure.html
 */
return new class extends Migration
{
    /**
     * up builds the migration
     */
    public function up()
    {
        Schema::create('appchat_conversation_conversations', function (Blueprint $table) {
            $table->engine = 'InnoDB'; // Nutné pre foreign keys

            $table->bigIncrements('id');

            $table->unsignedBigInteger('user_one_id');
            $table->unsignedBigInteger('user_two_id');

            $table->string('name')->default('Konverzácia');

            $table->timestamps();

            $table->foreign('user_one_id')
                ->references('id')->on('appuser_user_users')
                ->onDelete('cascade');

            $table->foreign('user_two_id')
                ->references('id')->on('appuser_user_users')
                ->onDelete('cascade');
        });
    }

    /**
     * down reverses the migration
     */
    public function down()
    {
        Schema::dropIfExists('appchat_conversation_conversations');
    }
};
