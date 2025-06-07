<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Player;
use App\Models\PlayerInvite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use Livewire\Component;
use Illuminate\Validation\Rule;


class PreRegisterForm extends Component
{
    public $token;

    // Данные формы
    public $email;
    public $firstname;
    public $lastname;
    public $password;
    public $birthday;
    public $phone;
    public $tg;
    public $rating;

    public PlayerInvite $invite;
    public Player $player;

    public function mount($token)
    {
        $this->token = $token;

        $this->invite = PlayerInvite::where('token', $token)
            ->where('used', false)
            ->firstOrFail();

        $this->player = $this->invite->player;

        // Подставим данные, если уже есть
        $this->tg = $this->player->tg ?? '';
        $this->firstname = $this->player->first_name ?? '';
        $this->lastname = $this->player->last_name ?? '';
        $this->rating = $this->player->rating ?? '';
        $this->phone = $this->player->phone ?? '';
        $this->birthday = $this->player->birthday ?? '';
    }

    public function submit()
    {
        // $cleanPhone = preg_replace('/[^\d+]/', '', $this->phone);
        $cleanPhone = preg_replace('/\D+/', '', $this->phone); // только цифры
        if (Str::startsWith($cleanPhone, '0')) {
            $cleanPhone = '+38' . $cleanPhone;
        } elseif (!Str::startsWith($cleanPhone, '+')) {
            $cleanPhone = '+' . $cleanPhone;
        }

        $validated = Validator::make([
            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'password' => $this->password,
            'birthday' => $this->birthday,
            'phone' => $cleanPhone,
            'tg' => $this->tg,
            'rating' => $this->rating,
        ], [
            'email' => 'required|email|unique:users,email',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'password' => 'required|min:6',
            'birthday' => 'required|date|before:2019-01-01|after:1950-01-01',
            'phone' => ['required', 'regex:/^\+380\d{9}$/'],
            'tg' => [
                'required',
                'string',
                'max:255',
                Rule::unique('players', 'tg')->ignore($this->player->id),
            ],
            'rating' => 'required|integer|min:1|max:80',
        ])->validate();

        // Создание пользователя
        $user = User::create([
            'name' => $this->player->last_name,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'viewer',
        ]);

        // Обновление игрока
        $this->player->update([
            'user_id' => $user->id,
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'phone' => $validated['phone'],
            'birth_date' => \Carbon\Carbon::parse($validated['birthday'])->format('Y-m-d'),
            'tg' => $validated['tg'],
            'rating' => $validated['rating'],
        ]);

        // Пометка как использованный
        $this->invite->update([
            'used' => true,
        ]);

        // Email верификация
        event(new Registered($user));

        auth()->login($user);

        return redirect()->route('verification.notice');
    }

    public function render()
    {
        return view('livewire.pre-register-form')->extends('layouts.app')->section('content');
    }
}
