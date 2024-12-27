<?php

namespace App\Models;

use App\Notifications\SendConfirmationCodeNotification;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

class UserSettingChange extends Model
{
    protected $fillable = [
        'user_setting_id', 'new_value', 'confirmation_code', 'confirmed_at', 'method'
    ];

    protected $casts = [
        'confirmed_at' => 'datetime'
    ];

    public function setting(): belongsTo {
        return $this->belognsTo(UserSetting::class);
    }

    public function updateSetting(Request $request, UserSetting $setting)
    {
        $data = $request->validate([
            'new_value' => 'required',
            'method' => 'required|in:sms,email,telegram'
        ]);

        $code = rand(100000, 999999);

        $change = $setting->changes()->create([
            'new_value' => $data['new_value'],
            'confirmation_code' => $code,
            'method' => $data['method']
        ]);

        Notification::send($setting->user, new SendConfirmationCodeNotification($code, $data['method']));

        return response()->json(['message' => 'Confirmation code sent.', 'change_id' => $change->id]);
    }

    public function confirmChange(Request $request, UserSettingChange $change)
    {
        $data = $request->validate([
            'confirmation_code' => 'required|digits:6'
        ]);

        if ($change->confirmation_code !== $data['confirmation_code']) {
            return response()->json(['error' => 'Invalid confirmation code.'], 422);
        }

        $change->update(['confirmed_at' => Carbon::now()]);
        $change->setting->update(['value' => $change->new_value]);

        return response()->json(['message' => 'Setting updated successfully.']);
    }
}
