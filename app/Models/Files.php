<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    protected $table = 'files';
	protected $guarded = ['id'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'taggable_id', 'taggable_type', 'name', 'type', 'original_name',
    ];

    public function taggable()
    {
        return $this->morphTo();
    }
    /**
     * Get all of the posts that are assigned this tag.
     */
    public function Unit()
    {
        return $this->morphedByMany('App\Models\Unit', 'taggable');
    }
    /**
     * Get all of the posts that are assigned this tag.
     */
    public function Offer()
    {
        return $this->morphedByMany('App\Models\Offer', 'taggable');
    }
    /**
     * Get all of the posts that are assigned this tag.
     */
    public function Ticket()
    {
        return $this->morphedByMany('App\Models\Ticket', 'taggable');
    }
    /**
     * Get all of the posts that are assigned this tag.
     */
    public function EngPost()
    {
        return $this->morphedByMany('App\Models\EngPost', 'taggable');
    }
    /**
     * Get all of the posts that are assigned this tag.
     */
    public function UnitContract()
    {
        return $this->morphedByMany('App\Models\UnitContract', 'taggable');
    }
    /**
     * Get all of the posts that are assigned this tag.
     */
    public function Investor()
    {
        return $this->morphedByMany('App\Models\Investor\Investor', 'taggable');
    }
    /**
     * Get all of the posts that are assigned this tag.
     */
    public function InvestorUnit()
    {
        return $this->morphedByMany('App\Models\Investor\InvestorsUnit', 'taggable');
    }
    /**
     * Get all of the posts that are assigned this tag.
     */
    public function Admin()
    {
        return $this->morphedByMany('App\Models\Admin', 'taggable');
    }
    /**
     * Get all of the posts that are assigned this tag.
     */
    public function ProjectOffer()
    {
        return $this->morphedByMany('App\Models\ProjectOffer', 'taggable');
    }
}
