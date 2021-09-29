<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'settings';

    /**
     * @var array
     */
    protected $fillable = ['code', 'description', 'title', 'value'];

    /**
     * @var array
     */
    protected $hidden = [];
}
