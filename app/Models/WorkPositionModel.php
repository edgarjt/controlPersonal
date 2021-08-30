<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkPositionModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'workPosition';

    /**
     * @var array
     */
    protected $fillable = ['area', 'name', 'description', 'code', 'status'];

    /**
     * @var array
     */
    protected $hidden = [];
}
