<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperGood
 */
class Good extends Model
{
    //

    protected $fillable = [
        'g_name', 'mark', 'shop_id'
    ];

}
