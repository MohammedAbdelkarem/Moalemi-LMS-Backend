<?php

namespace App\Models\System\CustomerService;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerServiceMessage extends Model
{
    use HasFactory;
    protected $table = "customer_card_messages";
    protected $guarded = ['id'];
    public function card(): BelongsTo
    {
        return $this->belongsTo(CustomerServiceCard::class, "card_id");
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
