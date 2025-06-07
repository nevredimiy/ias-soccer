<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PlayerController extends Controller
{
    public function create()
    {
        return view('players.create'); // Форма для ввода данных
    }

    public function store(Request $request)
    {
        $request->merge([
            'day' => intval($request->day),
            'month' => intval($request->month),
            'year' => intval($request->year),
        ]);

        // Нормализация номера
        $cleanPhone = preg_replace('/\D+/', '', $request->phone); // только цифры

        if (Str::startsWith($cleanPhone, '0')) {
            $cleanPhone = '+38' . $cleanPhone;
        } elseif (!Str::startsWith($cleanPhone, '+')) {
            $cleanPhone = '+' . $cleanPhone;
        }

        // Заменяем phone в $request на нормализованный
        $request->merge([
            'phone' => $cleanPhone,
        ]);

        $request->validate([
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'day' => 'required|integer|min:1|max:31',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'photo' => 'nullable|image|max:2048',
            'phone' => ['required', 'regex:/^\+380\d{9}$/'],
            'tg' => 'required|string|max:255',
        ], [], [
            'tg' => 'Телеграм',
            'phone' => 'Телефон',
            'firstname' => 'Ім\'я',
            'lastname' => 'Прізвище',
            'photo' => 'Фото',
        ]);

        // Проверка корректной даты
        if (!checkdate($request->month, $request->day, $request->year)) {
            return back()->withErrors(['birth_date' => 'Некорректная дата рождения'])->withInput();
        }

        $birthDate = sprintf('%04d-%02d-%02d', $request->year, $request->month, $request->day);

        $user = auth()->user();

        $player = new \App\Models\Player();
        $player->user_id = $user->id;
        $player->last_name = $request->lastname;
        $player->first_name = $request->firstname;
        $player->phone = $request->phone;
        $player->birth_date = $birthDate;
        $player->tg = $request->tg;

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('img/avatars', 'public');
            $player->photo = $path;
        }

        $player->rating = $request->rating;

        $player->save();

        return redirect()->route('profile')->with('success', 'Данные успешно сохранены!');
    }

    public function edit()
    {
        $user = Auth::user();
        $player = $user->player()->first();

        return view('players.edit', compact('user', 'player'));
    }

    public function update(Request $request)
    {

        
        $request->validate([
            'first_name' => 'string|max:255|min:1',
            'last_name'  => 'string|max:255|min:1',
            'email'      => 'email|max:255|unique:users,email,' . Auth::id(),
            'photo'      => 'nullable|image|max:2048',
            'birth_date' => 'nullable|date|before:today',
            'phone' => ['required', 'regex:/^\+380\d{9}$/'],
            'tg' => 'required|string|max:255',
            
        ]);

        $user = Auth::user();
        $player = $user->player()->first();

        $user->email = $request->email;
        $user->save();

        if ($player) {

            if ($request->has('first_name')) {
                $player->first_name = $request->first_name;
            }

            if ($request->has('last_name')) {
                $player->last_name = $request->last_name;
            }

            if ($request->has('birth_date')) {
                $player->birth_date = $request->birth_date;
            }

            if ($request->has('phone')) {
                $player->phone = $request->phone;
            }

            if ($request->has('tg')) {
                $player->tg = $request->tg;
            }

            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('players', 'public');
                $player->photo = $path;
            }

            $player->save();
        }

        return redirect()->route('players.edit')->with('success', 'Профіль оновлено');
    }

    
}
