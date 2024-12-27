<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserSetting extends Model
{
    protected $fillable = [
        'user_id', 'key', 'value'
    ];

    public function user(): belongsTo {
        return $this->belongsTo(User::class);
    }

    public function changes(): hasMany {
        return $this->hasMany(UserSeetingChange::class);
    }
}
