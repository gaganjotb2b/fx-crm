<?php

namespace App\Models;

use App\Models\Traders\SocialLink;
use App\Services\AllFunctionService;
use App\Services\CombinedService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Console\Migrations\StatusCommand;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Activitylog\Contracts\Activity;

use function PHPUnit\Framework\returnSelf;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, LogsActivity;
    protected $table = "users";

    // user activity log---------------------------------------------------
    // package use spatie
    // log the change attributes for all events
    protected static $recordEvents = ['created', 'updated', 'deleted'];

    // Customize log description
    public function getDescriptionForEvent(string $eventName): string
    {
        $ip_address = request()->ip();
        return "The IP address $ip_address has been {$eventName} user";
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timesptamp = true;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'country_code',
        'type',
        'password',
        'live_status',
        'transaction_password',
        'active_status',
        'login_status',
        'email_verified_at',
        'g_auth',
        'email_auth',
        'secret_key',
        'email_verification',
        'deposit_operation',
        'withdraw_operation',
        'commission_operation',
        'internal_transfer',
        'tmp_pass',
        'tmp_tran_pass',
        'category_id',
        'client_type',
        'app_investment',
        'trading_ac_limit',
        'client_group_id',
        'remember_token',
        'ib_group',
        'combine_access',
        'popup_id',
        'admin_log',
        'client_groups',
        'is_lead',
        'ip_address',
    ];

    // get activity log option and create log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])->useLogName('user')->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'client_group_id',
        'app_investment'
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    /**
     * Interact with the user's first name.
     *
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function type(): Attribute
    {
        return new Attribute(
            get: fn ($value) =>  ["trader", "system", "admin", "corporate", "ib", "manager","admin_user","country_manager"][$value],
        );
    }

    public function traderDeposit()
    {
        return $this->hasMany(TraderDeposit::class);
    }

    /*
    |----------------------------------------------------------------------
    | one to many relation with Withdraw Model
    |----------------------------------------------------------------------
    */
    public function withdraws()
    {
        return $this->hasMany(Withdraw::class);
    }

    public function internalTransfer()
    {
        return $this->hasMany(InternalTransfer::class);
    }

    public function tradingAccount()
    {
        return $this->hasMany(TradingAccount::class, 'user_id', 'id');
    }

    public function adminDetail()
    {
        return $this->hasMany('App\Models\Admin');
    }

    // email template relations
    // ------------------------------------------------------------------------
    public function email_templates()
    {
        return $this->hasMany(EmailTemplate::class, 'created_by');
    }
    // online bank
    public function online_banks()
    {
        return $this->hasMany(OnlineBank::class, 'created_by');
    }
    // deposit settings
    public function deposit_settings()
    {
        return $this->hasMany(DepositSetting::class, 'created_by');
    }
    //withdraw settings
    public function withdraw_settings()
    {
        return $this->hasMany(WithdrawSetting::class, 'created_by');
    }
    // crypto currencys
    public function crypto_currency()
    {
        return $this->hasMany(CryptoCurrency::class, 'created_by');
    }


    // sub ibs relation
    // for create sub ib
    public function sub_ib_create()
    {
        return $this->hasMany(IB::class, 'ib_id');
    }
    // trader settings relations
    // -----------------------------------------------------------
    public function cust_permission()
    {
        return $this->hasMany(TraderSetting::class, 'created_by');
    }
    // ib settings relations
    public function cust_permission_ib()
    {
        return $this->hasMany(IbSetting::class, 'created_by');
    }
    // admin module setup
    public function admin_permission()
    {
        return $this->hasMany(SystemModule::class, 'created_by');
    }
    // kyc id type relations
    // ------------------------------------------------------------------------
    public function kyc_id_type()
    {
        return $this->hasMany(KycIdType::class, 'created_by');
    }
    // kyc id type relations
    // ------------------------------------------------------------------------
    public function client_group()
    {
        return $this->hasMany(ClientGroup::class, 'created_by');
    }
    // ib relations
    // ------------------------------------------------------------------------
    public function ib_reference()
    {
        return $this->hasMany(IB::class, 'ib_id', 'reference_id');
    }
    // user description relations
    // ------------------------------------------------------------------------
    public function user_description()
    {
        return $this->hasOne(UserDescription::class, 'user_id');
    }

    public function managers()
    {
        return $this->hasMany(Manager::class, 'user_id');
    }
    // code for make sub ib
    // one level child
    public function child_ib()
    {
        return $this->hasMany(IB::class, 'ib_id')->with('child_ib');
    }
    // recursive children
    public function children_ib()
    {
        return $this->hasMany('App\Models\IB', 'ib_id', 'reference_id')->with('children_ib');
    }
    // one level parent
    public function parent()
    {
        return $this->belongsTo(IB::class, 'ib_id', 'reference_id');
    }
    // recursive parent
    public function parents_ib()
    {
        return $this->belongsTo(ib::class, 'ib_id')
            ->with('parents_ib');
    }
    // public function getPathAttribute()
    // {
    //     $path = [];
    //     if ($this->parent_id) {
    //         $parent = $this->parent;
    //         $parent_path = $parent->path;
    //         $path = array_merge($path, $parent_path);
    //     }
    //     $path[] = $this->name;
    //     return $path;
    // }
    // otp security check
    public function otpOptions()
    {
        return $this->hasOne(UserOtpSetting::class, 'user_id', 'id');
    }
    // finance operation check
    public function financeOptions()
    {
        return $this->hasOne(FinanceOp::class, 'user_id', 'id');
    }
    // ib account of this user email | or combined access = 1
    public function IbAccount()
    {
        return $this->hasOne(User::class, 'email', 'email')->where('type', CombinedService::type());
    }
    public function TraderAccount()
    {
        return $this->hasOne(User::class, 'email', 'email')->where('type', 0);
    }
    public function bankAccount()
    {
        return $this->hasMany(BankAccount::class, 'user_id', 'id');
    }
    public function otpCode()
    {
        return $this->hasOne(OtpCode::class, 'user_id', 'id');
    }
    public function myIb()
    {
        // return $this->hasMany(IB::class, 'ib_id', 'id');
        // return $this->belongsToMany(User::class,'ib','id','ib_id','reference_id','id');
        return $this->belongsToMany(User::class, 'ib', 'ib_id', 'reference_id', 'id', 'id');
    }
    public function masterIb()
    {
        // return $this->belongsToMany(User::class, 'ib', 'reference_id', 'ib_id');
        // return $this->hasOne(IB::class, 'reference_id', 'id');
        return $this->hasOneThrough(
            User::class, // The target model
            IB::class, // The intermediate model (pivot)
            'reference_id', // Foreign key on the IBUser table
            'id', // Foreign key on the User table
            'id', // Local key on the IB table
            'ib_id' // Local key on the IBUser table
        );
    }
    public function parentIb()
    {
        // return $this->belongsToMany(User::class, 'ib', 'reference_id', 'ib_id');
        // return $this->hasOne(IB::class, 'reference_id', 'id');
        return $this->hasOneThrough(
            User::class, // The target model
            IB::class, // The intermediate model (pivot)
            'reference_id', // Foreign key on the IBUser table
            'id', // Foreign key on the User table
            'id', // Local key on the IB table
            'ib_id' // Local key on the IBUser table
        );
    }
    // public function for get traders of ib
    public function traders()
    {
        return $this->belongsToMany(User::class, 'ib', 'ib_id', 'reference_id', 'id', 'id')->where('type', 0);
    }
    public function secureLog()
    {
        return $this->hasOne(Log::class, 'user_id', 'id');
    }
    public function description()
    {
        return $this->hasOne(UserDescription::class, 'user_id', 'id');
    }
    public function socialLink()
    {
        return $this->hasOne(SocialLink::class, 'user_id', 'id');
    }
    public function referLinks($ib_id = null)
    {
        $ib_referlink = null;
        if (!CombinedService::is_combined()) {
            $ib_referlink = AllFunctionService::ib_referel_link($ib_id);
        }
        return ([
            'ib_refer_link' => $ib_referlink,
            'trader_refer_link' => AllFunctionService::trader_referel_link($ib_id),
        ]);
    }
    public function IbIncome()
    {
        return $this->hasMany(IbIncome::class, 'ib_id', 'id');
    }

    public function referencesUser()
    {
        return $this->hasMany(IB::class, 'ib_id')->with(['referencesUser', 'referencesUser.referenceDetails', 'referencesUser.referenceDetails.tradingAccount']);
    }
    public function ibReference()
    {
        return $this->hasMany(IB::class, 'ib_id')->with(['ibReference', 'ibReference.referenceDetails']);
    }
     public function ibGroup() {
        return $this->belongsTo(IbGroup::class, 'ib_group_id');
    }
}
