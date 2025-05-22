<?php namespace AppChat\Message\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateMessagesTable Migration
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
        Schema::create('appchat_message_messages', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('conversation_id');
            $table->text('content')->nullable();
            $table->unsignedBigInteger('reply_to_id')->nullable();

            $table->timestamps();

            $table->foreign('conversation_id')
                  ->references('id')
                  ->on('appchat_conversation_conversations')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('appuser_user_users')
                  ->onDelete('cascade');

            $table->foreign('reply_to_id')
                  ->references('id')
                  ->on('appchat_message_messages')
                  ->onDelete('set null');
        });
    }

    /**
     * down reverses the migration
     */
    public function down()
    {
        Schema::dropIfExists('appchat_message_messages');
    }
};
