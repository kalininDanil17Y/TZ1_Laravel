<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'password',
        'last_seen'
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'password'
    ];


    public static function boot() {
        parent::boot();

        //static::created(function($item) {
        //    UserTrophie::create([ 'user_id' => $item['id'] ]);
        //    echo print_r($item['id']);
        //});
    }

    /**
     * @return HasMany
     */
    public function tropheis() {
        return $this->hasMany(UserTrophie::class);
    }

    /**
     * @param int $add
     * @return int
     */
    public function addTrophy(int $add): int
    {
        UserTrophie::create([ 'user_id' => $this->id, 'count' => $add ]);
    }

    /**
     * @param int $id
     * @return int
     */
    public function delTrophy(int $id): int
    {
        UserTrophie::destroy($id);
    }

}
