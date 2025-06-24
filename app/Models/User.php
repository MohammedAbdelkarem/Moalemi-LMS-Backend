<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use App\Enums\GenderEnum;
use App\Constants\Resources;
use App\Models\System\Info\FAQ;
use App\Models\System\Info\Tos;
use App\Models\System\Info\City;
use App\Models\System\Role\Role;
use App\Models\System\Info\AboutUs;
use App\Models\System\SystemSetting;
use Illuminate\Support\Facades\Auth;
use App\Models\System\Info\ContactUs;
use App\Models\System\Info\FaqCategory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Users\Profile\UserDevice;
use Illuminate\Notifications\Notifiable;
use App\Models\Administration\Log\BanLog;
use App\Models\System\Info\PrivacyPolicy;
use App\Models\Users\Profile\UserProfile;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Users\Profile\ArchivedUser;
use App\Models\Users\Profile\LoginHistory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\System\Notification\Notification;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Administration\Profile\AdminProfile;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\System\CustomerService\CustomerServiceCard;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $with = ["role"];

    protected $guarded = [
        'id',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    //JWT
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function jwtTokens(): HasMany
    {
        return $this->hasMany(JWTPersonalTokens::class, "user_id");
    }

        /**
     * Check if user is a regular user (role_id = 3)
     */
    public function isRegularUser(): bool
    {
        return $this->role_id === 3 || $this->role_id === 4;
    }

    /**
     * Check if user is an admin (role_id = 1 or 2)
     */
    public function isAdmin(): bool
    {
        return $this->role_id !== 3 && $this->role_id !== 4;
    }

    /**
     * Check if user is an doctor (role_id = 3)
     */
    public function isDoctor(): bool
    {
        return $this->role_id === 3;
    }
    /**
     * Check if user is an patient (role_id = 4)
     */
    public function isPatient(): bool
    {
        return $this->role_id === 4;
    }

    /**
     * Check if user is a super admin (role_id = 1)
     */
    public function isSuperAdmin(): bool
    {
        return $this->role_id === 1;
    }

    /**
     * Check if user is a regular admin (role_id = 2)
     */
    public function isRegularAdmin(): bool
    {
        return $this->role_id === 2;
    }

    public function isSystemAdmin(): bool
    {
        return !in_array($this->role_id, [3, 4]);
    }

    


    //Relations

    //Account Relations

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function loginHistory(): HasMany
    {
        return $this->hasMany(LoginHistory::class, "user_id");
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, "role_id");
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class, "user_id");
    }

    public function adminProfile(): HasOne
    {
        return $this->hasOne(AdminProfile::class, "user_id");
    }

    public function adminsCreated(): HasMany
    {
        return $this->hasMany(AdminProfile::class, "created_by");
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, "city_id");
    }

    public function userDevices(): HasMany
    {
        return $this->hasMany(UserDevice::class, "user_id");
    }

    public function archivedAccount(): HasOne
    {
        return $this->hasOne(ArchivedUser::class, "user_id");
    }

    public function otp(): HasOne
    {
        return $this->hasOne(OTP::class, 'user_id');
    }

    //Notifications
    public function notifications(): BelongsToMany
    {
        return $this->belongsToMany(Notification::class, 'user_notification')
            ->withTimestamps()
            ->withPivot('is_read');
    }

    public function createdNotifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'created_by');
    }

    //System Info Relations
    public function appContacts(): HasMany
    {
        return $this->hasMany(ContactUs::class, 'created_by');
    }

    public function appAbouts(): HasMany
    {
        return $this->hasMany(AboutUs::class, 'update_by');
    }

    public function appFaqCategories(): HasMany
    {
        return $this->hasMany(FaqCategory::class, 'update_by');
    }

    public function appFAQs(): HasMany
    {
        return $this->hasMany(FAQ::class, 'update_by');
    }

    public function appTos(): HasMany
    {
        return $this->hasMany(Tos::class, 'update_by');
    }

    public function appPrivacies(): HasMany
    {
        return $this->hasMany(PrivacyPolicy::class, 'update_by');
    }

    public function systemSettingsUpdated(): HasMany
    {
        return $this->hasMany(SystemSetting::class, 'update_by');
    }

    //Customer Service Cards
    public function CustomerServiceCard(): HasMany
    {
        return $this->hasMany(CustomerServiceCard::class, "user_id");
    }


    //Ban System
    public function bans(): HasMany
    {
        return $this->hasMany(BanLog::class, "banned_id");
    }

    public function bannedByMe(): HasMany
    {
        return $this->hasMany(BanLog::class, "banned_by_id");
    }

    public function unbannedByMe(): HasMany
    {
        return $this->hasMany(BanLog::class, "unbanned_by_id");
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    public function ArticleViews()
    {
        return $this->hasMany(ArticleView::class);
    }

    //Scopes

    /**
     * Search users by given search criteria
     * This scope will search 'name' and email columns
     * with LIKE operator and case insensitive
     *
     * @param Builder $query
     * @param string|null $search
     */
    public function scopeSearchName(Builder $query, string|null $search)
    {
        $query->when($search, function (Builder $q) use ($search) {
            $q->where("name", 'like', '%' . strtolower($search) . '%')
                ->orWhere("phone_number", "like", "%$search%");
            if (Auth::user() && Auth::user()->role_id != 3)
                $q->orWhere("email", "like", "%$search%");
        });
    }

    /**
     * Search users by given role(s) and filter by criteria below
     * 1. User account should be completed ('name' is not null)
     * 2. User have an active account (deactive_at is null)
     * 3. User account should be verified (account_verified_at is not null)
     *
     * @param Builder $query
     * @param array $role_id
     *
     */
    public function scopeUsersSearchCriteria(Builder $query, $checkBan = true)
    {
        $query->whereIn("role_id", [3,4])
            ->whereNotNull(['name', 'account_verified_at'])            //User account is completed and active
            ->whereNull("deactive_at")              //User have an active account
            ->when($checkBan, function (Builder $q) {
                $q->whereHas("profile", function ($query) {
                    $query->where("banned_until", "<", Carbon::now())
                        ->orWhereNull("banned_until");
                });
            });
    }

    public function scopeFilter($query, $data)
    {
        return $query

        ->when(isset($data['search']) , function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('phone_number', 'like', '%' . $search . '%');
        });
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function Patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function Doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    public static function findByIdOrFail($id, $with = [], $withTrashed = false, $selectedColumns = null)
    {
        return findByIdOrFail(
            self::class,
            $id,
            GenderEnum::MALE,
            Resources::RES_USER,
            $with,
            $withTrashed,
            $selectedColumns
        );
    }
}
