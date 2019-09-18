<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaQueue extends Model
{
    /**
     * The database table
     *
     * @var string
     */
    protected $table = 'media_queue';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type', 'process_media', 'model_id', 'model', 'remote_path'];
    
}
