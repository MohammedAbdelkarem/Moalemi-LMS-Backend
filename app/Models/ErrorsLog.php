<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorsLog extends Model
{
    protected $table = 'errors_log';
    protected $fillable = ['message', 'file', 'line', 'trace', 'err_code'];
}
