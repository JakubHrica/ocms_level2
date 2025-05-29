<?php namespace AppUser\User\Models;

use Model;
use October\Rain\Database\Traits\Hashable;

/**
 * User Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class User extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use Hashable;

    /**
     * @var string table name
     */
    public $table = 'appuser_user_users';

    /**
     * @var array rules for validation
     */
    public $rules = [
        'email' => 'required|email',
        'password' => 'min:6' // REVIEW - tu si odstránil required, myslím že to tu malo ostať, keď tak mi napíš prečo si sa rozhodol to odstrániť
    ];

    protected $hashable = ['password'];
}
