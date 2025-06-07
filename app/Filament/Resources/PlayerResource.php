<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlayerResource\Pages;
use App\Filament\Resources\PlayerResource\RelationManagers;
use App\Models\Player;
use App\Models\User;
use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;
use Illuminate\Validation\Rule;
use Filament\Forms\Components\Section;
use Illuminate\Support\Str;
use App\Models\PlayerInvite;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\URL;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;


use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;







class PlayerResource extends Resource
{
    protected static ?string $model = Player::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationGroup = 'Дані матчів';
    
    protected static ?string $navigationLabel = 'Гравці';
    
    protected static ?string $pluralModelLabel = 'Гравці';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Основні дані')
                ->schema([
                    Select::make('user_id')
                        ->label('Користувач')
                         ->options(
                            User::leftJoin('players', 'users.id', '=', 'players.user_id')
                                ->whereNull('players.user_id') // Только те, у кого нет игрока
                                ->select('users.id', 'users.name')
                                ->pluck('users.name', 'users.id')
                        )
                        ->searchable()
                        ->preload()
                        ->columnSpan([
                            'sm' => 2,
                        ]),
                    

                    Forms\Components\TextInput::make('last_name')
                        ->required()
                        ->maxLength(255)
                        ->label('Прізвище')
                        ->columnSpan([
                            'sm' => 2,
                        ]),
                    Forms\Components\TextInput::make('first_name')
                        ->required()
                        ->maxLength(255)
                        ->label('Ім\'я')
                        ->columnSpan([
                            'sm' => 2,
                        ]),
                ])->columns(6),
                PhoneInput::make('phone')
                    ->validateFor(
                        country: 'UA', // default: 'AUTO'
                        lenient: true, // default: false
                    )
                    ->onlyCountries(['ua'])
                    ->label('Телефон')
                    ->columnSpan([
                        'sm' => 2,
                    ]),
                Forms\Components\TextInput::make('tg')
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->label('Телеграм')
                    ->columnSpan([
                        'sm' => 2,
                    ]),
                Forms\Components\DatePicker::make('birth_date')
                    ->label('День народження')
                    ->columnSpan([
                        'sm' => 2,
                    ]),
               
                Select::make('teams')
                    ->multiple()
                    ->label('Команда')
                    ->relationship('teams', 'name')
                    ->options(
                        Team::orderBy('id', 'desc')->get()->mapWithKeys(function ($team) {
                            return [
                                $team->id => "{$team->name} ({$team->id})"
                            ];
                        })
                    )
                    ->searchable(),
                Forms\Components\TextInput::make('rating')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(10)
                    ->label('Рейтинг'),
                Forms\Components\Toggle::make('verify_rating')
                    ->label('Рейтинг підтверджено')
                    ->inline(false),               
                Forms\Components\FileUpload::make('photo')
                    ->image()
                    ->disk('public')
                    ->maxSize(2600)
                    ->directory('img/avatars')
                    ->deleteUploadedFileUsing(fn ($record) => 
                        $record->photo ? unlink(storage_path('app/public/' . $record->photo)) : null
                    )
                    ->columnSpan([
                        'sm' => 2,
                    ]),
            ])->columns(6);
    }

    public static function table(Table $table): Table
    {
        // dd();
        return $table
            ->columns([
                TextColumn::make('id')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Користувач') 
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.email')
                    ->label('Email') 
                    ->sortable()
                    ->searchable(),                    
                TextColumn::make('last_name')
                    ->label('Прізвище') 
                    ->sortable()
                    ->searchable(),
                TextColumn::make('first_name')
                    ->label('Ім\'я') 
                    ->sortable()
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Телефон') 
                    ->sortable()
                    ->searchable(),
                TextColumn::make('teams.name')                    
                    ->label('Назва команди')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->teams
                            ->map(fn ($team) => "{$team->name} ({$team->id})")
                            ->implode(', ');
                    })
                    ->toggleable(isToggledHiddenByDefault: true),               
                TextColumn::make('tg')
                    ->searchable()
                    ->label('Телеграм')
                    ->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('photo')
                    ->disk('public')
                    ->height(50)
                    ->width(50)
                    ->label('Фото')
                    ->toggleable(isToggledHiddenByDefault: true), 
                TextColumn::make('birth_date')
                    ->label('День народження') 
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('rating')
                    ->label('Рейтинг') 
                    ->sortable(),
                IconColumn::make('verify_rating')
                    ->label('Підтверджено')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Створено') 
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Оновлено') 
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('playerInvite.token')
                //     ->label('Ссылка')
                //     ->formatStateUsing(function ($state, $record) {
                //         $invite = $record->invite ?? \App\Models\PlayerInvite::firstOrCreate(
                //             ['player_id' => $record->id],
                //             ['token' => Str::uuid()]
                //         );
                //         return $invite->token;
                //     })
                //     ->copyable()
                //     ->copyMessage('Ссылка скопирована!')
                //     ->copyableState(fn (string $state): string => "https://ias-soccer/pre-register-form/{$state}")
                //     // ->visible(fn (Model $record) => $record->user_id === null)
                //     ->tooltip(fn (Model $record): string => "https://ias-soccer/pre-register-form/{$record->playerInvite?->token}")
                //     ,

                    
            ])

            ->filters([
                //
            ])
            ->actions([
                 Action::make('invite')
                    ->label('Получить ссылку')
                    ->icon('heroicon-o-link')
                    ->visible(fn ($record) => $record->user_id === null)
                    ->action(function ($record) {
                        $invite = $record->invite ?? \App\Models\PlayerInvite::firstOrCreate(
                            ['player_id' => $record->id],
                            ['token' => Str::uuid()],
                        );

                        $url = URL::route('pre-register-form', ['token' => $invite->token]);

                        Notification::make()
                            ->title('Ссылка для регистрации')
                            ->body("Скопируйте и отправьте ссылку: $url")
                            ->success()
                            ->send();
                    }),
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
               
                
                       
                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlayers::route('/'),
            'create' => Pages\CreatePlayer::route('/create'),
            'edit' => Pages\EditPlayer::route('/{record}/edit'),
        ];
    }
}
