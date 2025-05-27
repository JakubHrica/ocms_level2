<?php namespace AppChat\Message\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use AppUser\User\Models\User;
use AppChat\Conversation\Models\Conversation;
use AppChat\Message\Models\Message;

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

            $table->foreignIdFor(User::class, 'user_id');
            $table->foreignIdFor(Conversation::class, 'conversation_id');
            $table->text('content')->nullable();
            $table->foreignIdFor(Message::class, 'reply_to_id')->nullable();

            $table->timestamps();
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
