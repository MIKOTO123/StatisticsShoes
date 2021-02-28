<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperImportError
 */
class ImportError extends Model
{
    //

    protected $fillable = [
        's_id', 'error_row', 'error_reason'
    ];


}
