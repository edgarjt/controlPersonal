<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatRoleModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'cat_roles';

    /**
     * @var array
     */
    protected $fillable = ['name', 'code', 'description'];

    /**
     * @var array
     */
    protected $hidden = [];
}
