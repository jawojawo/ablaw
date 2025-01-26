<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use LogsActivity;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'role',
        'email',
        'username',
        'initial_password',
        'password',
        'address',
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'role', 'username', 'address']);
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function lawCases()
    {
        return $this->hasMany(LawCase::class);
    }
    public function adminDeposits()
    {
        return $this->hasMany(AdminDeposit::class)->orderBy('deposit_date', 'desc');
    }
    public function adminFees()
    {
        return $this->hasMany(AdministrativeFee::class);
    }
    public function hearings()
    {
        return $this->hasMany(Hearing::class);
    }
    public function billings()
    {
        return $this->hasMany(Billing::class);
    }
    public function customEvents()
    {
        return $this->hasMany(CustomEvent::class);
    }
    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }
    public function officeExpenses()
    {
        return $this->hasMany(OfficeExpense::class);
    }
    public function ownedNotes()
    {
        return $this->hasMany(Note::class);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
    public function hasPermissionForModel($model)
    {
        return $this->permissions()->where([
            'model' => $model,
        ])->exists();
    }
    public function hasPermissionFor($model, $permission)
    {
        return $this->permissions()->where([
            'model' => $model,
            'permission' => $permission,
        ])->exists();
    }

    public function contacts()
    {
        return $this->morphMany(Contact::class, 'contactable');
    }
    public function notes()
    {
        return $this->morphMany(Note::class, 'notable');
    }
    public function getFullnameAttribute()
    {
        return "{$this->first_name} {$this->last_name} {$this->suffix}";
    }
}
