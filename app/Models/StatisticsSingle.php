<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperStatisticsSingle
 */
class StatisticsSingle extends Model
{
    //
    protected $fillable = [
        's_id', 'shop_name', 'g_name', 'color', 'count'
    ];
}
