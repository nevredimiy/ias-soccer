<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeriesMetaResource\Pages;
use App\Filament\Resources\SeriesMetaResource\RelationManagers;
use App\Models\SeriesMeta;
use Filament\Forms;
use App\Models\Event;
use App\Models\City;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Resources\Resource;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SeriesMetaResource extends Resource
{
    protected static ?string $model = SeriesMeta::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Дані матчів';

    protected static ?int $navigationSort = 7;

    protected static ?string $navigationLabel = 'Дані серії';

    protected static ?string $pluralModelLabel = 'Дані серії';
  
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
                Select::make('event_id')
                    ->preload()
                    ->options(
                        Event::with('tournament')
                            ->orderBy('id', 'desc')
                            ->get()
                            ->mapWithKeys(function ($event) {
                                return [
                                    $event->id => $event->tournament->name . ' (' . $event->id . ')',
                                ];
                            })
                            ->toArray()
                    )
                    ->searchable()
                    ->label('Подія')
                    ->required()
                    ->columnSpan('full'),
                    Section::make('Локація')
                    ->description('Виберіть стадіон для серії')
                    ->columns(3)
                    ->schema([
                        // Місто
                        Select::make('city_id')
                            ->label('Місто')
                            ->options(City::pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive(),
                
                        // Район
                        Select::make('district_id')
                            ->label('Район')
                            ->options(function (callable $get) {
                                $cityId = $get('city_id');
                                return $cityId
                                    ? \App\Models\District::where('city_id', $cityId)->pluck('name', 'id')
                                    : [];
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->disabled(fn (callable $get) => !$get('city_id')),
                
                        // Локація
                        Select::make('location_id')
                            ->label('Локація')
                            ->options(function (callable $get) {
                                $districtId = $get('district_id');
                                return $districtId
                                    ? \App\Models\Location::where('district_id', $districtId)->pluck('address', 'id')
                                    : [];
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->disabled(fn (callable $get) => !$get('district_id')),
                
                        // Стадіон
                        Select::make('stadium_id')
                            ->label('Стадіон')
                            ->options(function (callable $get) {
                                $locationId = $get('location_id');
                                return $locationId
                                    ? \App\Models\Stadium::where('location_id', $locationId)->pluck('name', 'id')
                                    : [];
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn (callable $get) => !$get('location_id'))
                            ->columnSpan('2'),
                        Select::make('size_field')
                            ->label('Розмір поля')
                            ->options([
                                '40x20' => '40x20',
                                '60x40' => '60x40'
                            ])
                            ->default('40x20')
                            ->required(),
                    ]),
               
                TextInput::make('series')
                    ->required()
                    ->numeric()
                    ->label('Серія')
                    ->default(1),
                TextInput::make('round')
                    ->required()
                    ->numeric()
                    ->label('Раунд')
                    ->default(1),
                Select::make('status_registration')
                    ->label('Статус реєстрації')
                    ->options([
                        'open' => 'Відкритий',
                        'closed' => 'Закритий'
                    ])
                    ->default('open')
                    ->required(),
                DateTimePicker::make('start_date')
                    ->required()
                    ->label('Дата початку'),
                DateTimePicker::make('end_date')
                    ->required()
                    ->label('Дата закінчення'),
                TextInput::make('price')
                    ->label('Ціна')
                    ->required()
                    ->numeric()
                    ->default(0),
                    
              
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stadium.name')
                    ->label('Стадіон')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stadium.location.district.city.name')
                    ->label('Місто')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stadium.location.district.name')
                    ->label('Район')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stadium.location.address')
                    ->label('Адреса')
                    ->sortable(),
                Tables\Columns\TextColumn::make('size_field')
                    ->label('Розмір поля')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('series')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('round')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('UAH')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_registration')                
                    ->label('Статус')
                    ->formatStateUsing(fn ($state) => [
                        'open' => 'Відкритий',
                        'closed' => 'Закритий'
                    ][$state] ?? $state)
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
            'index' => Pages\ListSeriesMetas::route('/'),
            'create' => Pages\CreateSeriesMeta::route('/create'),
            'edit' => Pages\EditSeriesMeta::route('/{record}/edit'),
        ];
    }
}
