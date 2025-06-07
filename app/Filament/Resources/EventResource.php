<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use App\Models\Tournament;
use App\Models\League;
use App\Models\Stadium;
use App\Models\TeamColor;
use App\Models\Team;
use App\Models\Matche;
use App\Models\SeriesMeta;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationLabel = 'Подія';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('tournament_id')
                    ->options(
                        Tournament::orderBy('sort_order')->get()->mapWithKeys(function ($tournament) {
                            $tournamentType = $tournament->type == 'team' ? 'командний' : 'одиночний';
                            return [
                                $tournament->id => $tournament->name . ' (' . $tournamentType . ')',
                            ];
                        })->toArray()
                    )
                    ->searchable()
                    ->label('Турнір')
                    ->required()
                    ->columnSpan('full')
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state) {
                        $tournament = Tournament::find($state);
                        if ($tournament) {                       
                            
                            // Установка количества команд в зависимости от турнира
                            $countTeams = $tournament->count_teams ?? 0;

                            $teamColors = TeamColor::all()->pluck('name')->values()->all(); // переиндексация

                            if ($tournament->team_creator == 'admin') {
                                $teams = [];
                                $teamNames = [
                                    "Синій"=>"Синіх",
                                    "Червоний"=>"Червоних",
                                    "Зелений"=>"Зелених",
                                    "Жовтий"=>"Жовтих",
                                    "Помаранчевий"=>"Помаранчевих",
                                    "Рожевий"=>"Рожевих",
                                    "Сірий"=>"Сірих",
                                    "Лаймовий"=>"Лаймових",
                                    "Голубий"=>"Блакитних",
                                ];

                                for ($i = 0; $i < $countTeams; $i++) {
                                    $colorName = $teamColors[$i] ?? null;

                                    if (!isset($teamNames[$colorName])) {
                                        continue; // пропустить, если название цвета не найдено
                                    }

                                    $teams[] = [
                                        'name' => 'Команда ' . $teamNames[$colorName],
                                        'color_id' => $i + 1,
                                    ];
                                }

                                $set('teams', $teams);
                            } else {
                                $set('teams', []);
                            }

                            $prices = [];
                            for ($i = 0; $i < $countTeams; $i++) {
                                $prices[] = ['price' => 300]; // или установить значение по умолчанию
                            }
                            $set('team_prices', $prices);


                            $set('tournament_team_creator', $tournament->team_creator);
                            $set('is_private', $tournament->type == 'solo_private');
                            $set('tournament_type', $tournament->type);
                            $set('tournament_subtype', $tournament->subtype);
                            $set('count_series', $tournament->count_series);
                            $set('count_teams', $tournament->count_teams);
                        }
                    }),
    
                Section::make('Інформація Серії')
                    ->schema([
                        DateTimePicker::make('series_start_all')
                            ->label('Дата початку для всіх Серій')
                            ->seconds(false)
                            ->required(),                       
                        DateTimePicker::make('series_end_all')
                            ->label('Дата кінця Серії')
                            ->seconds(false),
                            
                        TextInput::make('series_price_all')
                            ->label(function (callable $get) {
                                $count_series = $get('count_series') ?? 0;
                                return $count_series === 1 ? 'Ціна для Серії' : 'Ціна для всіх Серій';
                            })
                            ->numeric()
                            ->visible(fn (callable $get) => $get('tournament_type') === 'team')
                            ->required(fn (callable $get) => $get('tournament_type') === 'team'),

                        TextInput::make('player_price')
                            ->label('Ціна гравця')
                            ->numeric()
                             ->default(366)
                            ->visible(fn (callable $get) => $get('tournament_type') !== 'team')
                            ->required(fn (callable $get) => $get('tournament_type') !== 'team'),

                        Select::make('stadium_id')
                            ->label('Стадіон для всіх Серій')
                            ->options(function () {
                                return Stadium::with(['location.district.city'])->get()->mapWithKeys(function ($stadium) {
                                    $location = $stadium->location;
                                    $district = $location?->district;
                                    $city = $district?->city;
                                    $label = $stadium->name;
                                    if ($location) $label .= ' - ' . $location->address;
                                    if ($district) $label .= ' - ' . $district->name;
                                    if ($city) $label .= ' - ' . $city->name;
                                    return [$stadium->id => $label];
                                });
                            })
                            ->searchable()
                            ->default(1),
                        Select::make('league_id')
                            ->label('Ліга для всіх Серій')
                            ->options(League::all()->pluck('name', 'id'))
                            ->searchable(),                         
                        Select::make('size_field')
                            ->options(['40x20' => '40x20', '60x40' => '60x40'])
                            ->default('40x20')
                            ->dehydrated()
                            ->label('Розмір стадіона'),   
                        Select::make('custom_count_rounds')
                            ->label('Кількість раундів')
                            ->options([
                                1 => '1',
                                2 => '2',
                                3 => '3',
                                4 => '4',
                                5 => '5',
                                6 => '6',
                                7 => '7',
                                8 => '8',
                                9 => '9',
                                10 => '10',
                                11 => '11',
                                12 => '12',
                                13 => '13',
                                14 => '14',
                                15 => '15',
                                16 => '16',
                                17 => '17',
                                18 => '18',
                                19 => '19',
                                20 => '20',
                            ])
                            ->visible(fn (callable $get) =>
                                $get('tournament_type') === 'solo' && $get('tournament_subtype') === 'regular'
                            )
                            ->default(15)
                    ])->columns(3),

                Section::make('Інформація події')
                    ->schema([
                        TextInput::make('name')
                            ->label('Назва події')
                            ->maxLength(255),                            
                        Select::make('format')
                            ->options(['5x5x5' => '5x5x5', '4x4x4' => '4x4x4', '9x9x9' => '9x9x9'])
                            ->default('5x5x5')
                            ->dehydrated()
                            ->label('Формат'), 
                        TextInput::make('access_code')
                            ->label('Код доступу для створення команди')
                            ->numeric()
                            ->inputMode('numeric') // важно для мобильных устройств
                            ->maxLength(4)
                            ->rules(['nullable', 'digits:4'])
                            ->visible(fn (callable $get) => $get('is_private')),
                        Select::make('status')
                            ->label('Статус')
                            ->options([
                                'upcoming' => 'Заплановано',
                                'active' => 'Активний', 
                                'finished' => 'Завершено'
                            ])
                            ->default('upcoming'),
                     
                    ])->columns(3),
                // Секция для команд. Количество команд зависит от турнира
                Section::make('Ціни першого внеску для команд')
                    ->schema([
                        Forms\Components\Repeater::make('team_prices')
                            ->label('Ціни команд по порядку')
                            ->hidden(fn ($get) => empty($get('team_prices')))
                            ->schema([
                                TextInput::make('price')
                                    ->label(fn ($get) => 'Ціна команди ')
                                    ->numeric(),
                                ])
                            ->columns(1)
                            ->disableItemDeletion()
                            ->disableItemCreation()
                            ->disableItemMovement()
                            ->default(fn (callable $get) => array_fill(0, $get('count_teams') ?? 0, ['price' => null]))
                    ])
                    ->columns('full')
                    ->visible(fn (callable $get) => $get('tournament_type') === 'team'),
                Forms\Components\Repeater::make('team_prices')
                    ->label('Ціни команд')
                    ->schema([
                        TextInput::make('price')
                            ->label(fn ($get) => 'Ціна команди ')
                            ->numeric()
                            ->required(),
                    ])
                    ->default([]) // обязательно!
                    ->columns(1)
                    ->hiddenOn('create') // Показываем только при редактировании
                    ->disableItemDeletion()
                    ->disableItemCreation()
                    ->disableItemMovement(),
                Forms\Components\Repeater::make('teams')
                    ->label('Команды')
                    ->hidden(fn ($get) => empty($get('teams')))
                    ->schema([
                        TextInput::make('name')
                            ->label('Название команды')
                            ->required(),
                        Select::make('color_id')
                            ->label('Цвет команды')
                            ->options(TeamColor::all()->pluck('name', 'id'))
                            ->required(),
                    ])
                    ->disableItemDeletion()
                    ->disableItemCreation()
                    ->disableItemMovement()
                    ->default([])
                    ->columns(2),
                    
               
            ])
            ->columns('full');
    }
    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Назва події')
                    ->sortable(),
                TextColumn::make('tournament.name')                    
                    ->sortable(),
                TextColumn::make('format')
                    ->label('Формат')
                    ->sortable(),
                TextColumn::make('price')
                    ->numeric(decimalPlaces: 0)
                    ->sortable(),
                TextColumn::make('access_code')
                    ->label('Код доступу')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->label('Статус')
                    ->formatStateUsing(fn ($state) => [
                        'upcoming' => 'Заплановано',
                        'active' => 'Активний', 
                        'finished' => 'Завершено'
                    ][$state] ?? $state),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }

    public static function getMatchFormSchema(Event $record): array
    {
        $seriesCount = match ($record->format_scheme) {
            3 => 1,
            4 => 1,
            6 => 2,
            9 => 3,
            default => 1,
        };

        $form = [];

        for ($i = 1; $i <= $seriesCount; $i++) {
            $form[] = Fieldset::make("Серія $i")
            ->schema([
                DateTimePicker::make("series_date_$i")
                    ->label("Дата початку Серії $i")
                    ->required()
                    ->seconds(false),
                TextInput::make("series_price_$i")
                    ->label("Ціна Серії $i")
                    ->numeric()
                    ->required(),
            ])
            ->columns(2); // Показывает дату и цену в одной строке
        }

        return $form;
    }

}
