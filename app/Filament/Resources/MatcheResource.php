<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MatcheResource\Pages;
use App\Filament\Resources\MatcheResource\RelationManagers;
use App\Models\Matche;
use App\Models\Event;
use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Filament\Forms\Components\Section;

class MatcheResource extends Resource
{
    protected static ?string $model = Matche::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?string $navigationGroup = 'Дані матчів';
    
    protected static ?string $navigationLabel = 'Матчі';
    
    protected static ?string $pluralModelLabel = 'Список матчів';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Основний вибір')
                    ->description('Спочатку виберіть подію, потім серію і потім тур')
                    ->schema([
                        Forms\Components\Select::make('event_id')
                            ->options(function () {
                                return Event::with(['stadium'])
                                ->orderBy('id', 'desc')
                                ->get()
                                ->mapWithKeys(function ($event) {
                                    $stadium = $event->stadium;
                                    $location = $stadium->location;
                                    $district = $location->district;
                                    $city = $district ? $district->city : null;
                                    $label = $event->date;
                                    if($stadium) {
                                        $label .= ' - ' . $stadium->name;
                                    }
                                    if($location) {
                                        $label .= ' - ' . $location->address;
                                    }
        
                                    if($city) {
                                        $label .= ' - ' . $city->name;
                                    }
        
                                    return [$event->id => $label];
        
                                });
                            })
                            ->searchable()
                            ->live()
                            ->required()
                            ->label('Подія')
                            ->columnSpan(['sm' => 4]),
                        Forms\Components\Select::make('series')
                            ->label('Серія')
                            ->required()
                            ->reactive()
                            ->options(function (callable $get) {
                                $eventId = $get('event_id');
                        
                                if (!$eventId) {
                                    return [];
                                }
                        
                                $event = \App\Models\Event::find($eventId);
                        
                                $map = [
                                    4 => 1,
                                    6 => 2,
                                    9 => 3,
                                ];
                        
                                $count = $map[$event?->format_scheme ?? 4] ?? 1;
                        
                                return array_combine(range(1, $count), range(1, $count));
                            })
                            ->default(1),
                        Forms\Components\Select::make('round')
                            ->reactive()
                            ->required()
                            ->label('Тур')
                            ->options(function (callable $get) {
                                $eventId = $get('event_id');
                        
                                if (!$eventId) {
                                    return [];
                                }
                        
                                $event = \App\Models\Event::find($eventId);
                        
                                $map = [
                                    4 => 12,
                                    6 => 10,
                                    9 => 12,
                                ];
                        
                                $count = $map[$event?->format_scheme ?? 4] ?? 1;
                        
                                $rounds = array_combine(range(1, $count), range(1, $count));

                                // Добавим '0' => 'Фінал' в конец
                                $rounds[0] = 'Фінал';

                                return $rounds;
                            })
                            ->default(1),
                    ])->columns(6),
                
                Forms\Components\Select::make('team1_id')
                    ->options(fn (Get $get): array => Team::where('event_id', $get('event_id'))
                        ->pluck('name', 'id')
                        ->toArray())
                    ->label('Команда 1')
                    ->required(),
                Forms\Components\Select::make('team2_id')
                    ->options(fn (Get $get): array => Team::where('event_id', $get('event_id'))
                        ->pluck('name', 'id')
                        ->toArray())
                    ->label('Команда 2')
                    ->required(),                    
               
                Forms\Components\DateTimePicker::make('start_time')
                    ->label('Початок матчу')    
                    ->required(),
                Forms\Components\Select::make('status')
                    ->required()
                    ->default('scheduled')
                    ->label('Статус')
                    ->options([
                        'scheduled' => 'Заплановано',
                        'finished' => 'Закінчений',
                        'canceled' => 'Відмінений',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('team1_id')
                    ->label('Команда 1')
                    ->getStateUsing(function ($record) {
                        $team = Team::find($record->team1_id);
                        return $team ? $team->name . ' (' . $team->id . ')' : 'Не указана';
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('team2_id')
                    ->label('Команда 2')
                    ->getStateUsing(function ($record) {
                        $team = Team::find($record->team2_id);
                        return $team ? $team->name . ' (' . $team->id . ')' : 'Не указана';
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->dateTime('d F H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('series')
                    ->numeric()
                    ->label('Серія')
                    ->sortable(),
                Tables\Columns\TextColumn::make('round')
                    ->numeric()
                    ->label('Тур')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Статус')
                    ->formatStateUsing(fn ($state) => [
                        'scheduled' => 'Заплановано',
                        'finished' => 'Закінчений',
                        'canceled' => 'Відмінений',
                    ][$state] ?? $state)
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'scheduled' => 'warning',
                        'finished' => 'success',
                        'canceled' => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
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
            'index' => Pages\ListMatches::route('/'),
            'create' => Pages\CreateMatche::route('/create'),
            'view' => Pages\ViewMatche::route('/{record}'),
            'edit' => Pages\EditMatche::route('/{record}/edit'),
        ];
    }
}
