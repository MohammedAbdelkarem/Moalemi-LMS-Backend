<?php

namespace App\Models\System\Info;

use App\Models\User;
use App\Models\Users\Product\Product;
use App\Models\Users\Product\SavedSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory;
    protected $fillable = ["name_en", "name_ar"];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'city_id');
    }

    public function setNameEnAttribute($value): void
    {
        $this->attributes['name_en'] = ucfirst(strtolower($value));
    }
}
