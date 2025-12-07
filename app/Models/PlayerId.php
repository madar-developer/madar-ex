<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerId extends Model
{
    protected $table = 'player_ids';
    protected $primaryKey = 'id';
	protected $guarded = ['id'];
    public $sequence = 'xxccs_player_ids_id_seq';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'taggable_id', 'taggable_type', 'player_id', 'id',
    ];

    public function taggable()
    {
        return $this->morphTo();
    }
    /**
     * Get all of the posts that are assigned this tag.
     */
    public function User()
    {
        return $this->morphedByMany('App\Models\User', 'taggable');
    }
    /**
     * Get all of the posts that are assigned this tag.
     */
    public function Driver()
    {
        return $this->morphedByMany('App\Models\Driver', 'taggable');
    }
}
