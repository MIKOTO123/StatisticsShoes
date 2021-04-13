<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperShop
 */
class Shop extends Model
{
    //

    protected $fillable=[
        'shop_name','mark','area_id'
    ];

}
