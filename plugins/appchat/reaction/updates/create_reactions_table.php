<?php namespace AppChat\Reaction\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use AppChat\Message\Models\Message;
use AppUser\User\Models\User;

/**
 * CreateReactionsTable Migration
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
        Schema::create('appchat_reaction_reactions', function(Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Message::class, 'message_id');
            $table->foreignIdFor(User::class, 'user_id');
            $table->string('emoji');

            $table->timestamps();
        });
    }

    /**
     * down reverses the migration
     */
    public function down()
    {
        Schema::dropIfExists('appchat_reaction_reactions');
    }
};
