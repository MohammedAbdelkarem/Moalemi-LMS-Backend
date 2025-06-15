<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleView extends Model
{
    use HasFactory;
    
    protected $guarded = [
        'id'
    ];


    public function article()
    {
        return $this->belongsTo(Article::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // /**
    //  * @return \App\Models\ArticleView
    //  */
    // public static function findByIdOrFail($id, $with = [], $withTrashed = false, $selectedColumns = null)
    // {
    //     return findByIdOrFail(
    //         self::class,
    //         $id,
    //         GenderEnum::MALE,
    //         Resources::RES_MODEL,
    //         $with,
    //         $withTrashed,
    //         $selectedColumns
    //     );
    // }
}
