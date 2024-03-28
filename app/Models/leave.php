<?php

namespace App\Models;

use App\Events\LeaveCreated;
use App\Notifications\LeaveCreatedNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Notification;

class leave extends Model
{
    use HasFactory;
    protected $fillable = [
        'description',
        'date',
        'reason',
        'leavetype_id',
        'leavestatus_id',
        'leavepermission_id',
        'user_id',

    ];

    public function leavetype():BelongsTo
    {
        return $this->belongsTo(leavetype::class);
    }
    public function leavestatus():BelongsTo
    {
        return $this->belongsTo(leavestatus::class);
    }
    public function leavepermission():BelongsTo
    {
        return $this->belongsTo(leavepermission::class);
    }
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
