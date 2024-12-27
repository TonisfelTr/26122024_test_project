<?php

namespace App\Http\Controllers;

use App\Models\UserSetting;
use App\Models\UserSettingChange;
use App\Notifications\SendConfirmationCodeNotification;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class APIController extends Controller
{
    public function requestChange(Request $request)
    {
        $request->validate([
            'new_value' => 'required|string|max:255',
            'method' => 'required|in:email,sms,telegram',
        ]);


        $change = UserSettingChange::create([
            'user_id' => auth()->id(),
            'new_value' => $request->new_value,
            'method' => $request->method,
            // Генерация кода
            'confirmation_code' => rand(100000, 999999),
        ]);

        Notification::route($request->method, $this->getNotificationRecipient($request->method))
                    ->notify(new SendConfirmationCodeNotification($change->confirmation_code));

        return response()->json(['change_id' => $change->id]);
    }

    public function confirmChange(UserSettingChange $change, Request $request)
    {
        // Знаю, что не самый лучший подход, но в тестовом задании это допустимо, если объект один.
        $request->validate([
            'confirmation_code' => 'required|digits:6',
        ]);

        if ($change->confirmation_code !== $request->confirmation_code) {
            return response()->json(['message' => 'Неверный код подтверждения'], 422);
        }

        $user = $change->user;
        $user->update(['setting' => $change->new_value]);

        $change->delete();

        return response()->json(['message' => 'Настройка успешно обновлена!']);
    }

    private function getNotificationRecipient($method)
    {
        $user = auth()->user();

        return match ($method) {
            'email' => $user->email,
            'sms' => $user->phone_number,
            'telegram' => $user->telegram_chat_id,
        };
    }

    public function updateSetting(Request $request)
    {
        $request->validate([
            'new_value' => 'required|string|max:255',
            'method' => 'required|in:email,sms,telegram',
        ]);

        $change = UserSettingChange::create([
            'user_id' => auth()->id(),
            'new_value' => $request->new_value,
            'method' => $request->method,
            // Генерация кода
            'confirmation_code' => rand(100000, 999999),
        ]);

        Notification::route($request->method, $this->getNotificationRecipient($request->method))
                    ->notify(new SendConfirmationCodeNotification($change->confirmation_code));

        return response()->json(['change_id' => $change->id]);
    }
}
