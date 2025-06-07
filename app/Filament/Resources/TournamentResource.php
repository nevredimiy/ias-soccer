<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TournamentResource\Pages;
use App\Filament\Resources\TournamentResource\RelationManagers;
use App\Models\Tournament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;

class TournamentResource extends Resource
{
    protected static ?string $model = Tournament::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $navigationGroup = 'Дані матчів';

    protected static ?string $navigationLabel = 'Турнири';
    
    protected static ?string $pluralModelLabel = 'Список турнірів';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan('full'),
                Forms\Components\Select::make('type')
                    ->label('Тип турніра')
                    ->options([
                        'team' => 'Командний',
                        'solo' => 'Одиночний',
                        'solo_private' => 'Одиночний (приватний)',
                    ])
                    ->default('team')
                    ->required(),
               
                Forms\Components\Select::make('subtype')
                    ->label('Підтип турніра')
                    ->options([
                        'one-day' => 'Одноденний',
                        'regular' => 'Регулярний',
                    ])
                    ->default('regular')
                    ->required(),
                Section::make('Додаткові параметри')
                    ->description('Введіть параметри для турніру')
                    ->schema([
                        Forms\Components\Select::make('count_teams')
                            ->label('Кількість команд')
                            ->options([
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '5' => '5',
                                '6' => '6',
                                '7' => '7',
                                '8' => '8',
                                '9' => '9',
                                '10' => '10',
                            ])
                            ->default('3')
                            ->required(),
                        Forms\Components\Select::make('count_rounds')
                            ->label('Кількість турів')
                            ->options([
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '5' => '5',
                                '6' => '6',
                                '7' => '7',
                                '8' => '8',
                                '9' => '9',
                                '10' => '10',
                                '11' => '11',
                                '12' => '12',
                                '13' => '13',
                                '14' => '14',
                                '15' => '15',
                            ])
                            ->default('1')
                            ->required(),
                        Forms\Components\Select::make('count_series')
                            ->label('Кількість серій')
                            ->options([
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                            ])
                            ->default('1')
                            ->required(),
                        Forms\Components\Select::make('count_matches')
                            ->label('Кількість матчів')
                            ->options([
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '5' => '5',
                                '6' => '6',
                                '7' => '7',
                                '8' => '8',
                                '9' => '9',
                                '10' => '10',
                                '11' => '11',
                                '12' => '12',
                                '13' => '13',
                                '14' => '14',
                                '15' => '15',
                            ])
                            ->default('15')
                            ->required(),
                        Forms\Components\Select::make('team_creator')
                            ->label('Хто створює команду')
                            ->options([
                                'admin' => 'Адміністратор',
                                'player' => 'Гравець',
                            ])
                            ->default('admin')
                            ->required(),
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Порядок сортування')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->columnSpan('full'),
                            ])->columns(4),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->label('Назва турніру')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Тип турніра')
                    ->formatStateUsing(function ($state) {
                        switch ($state) {
                            case 'team' : 
                                return 'Командний';
                            case 'solo': 
                                return 'Одиночний';
                            default:
                            return 'Одиночний (приватний)';
                    }
                    })
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('subtype')
                    ->label('Підтип турніра')
                    ->formatStateUsing(function ($state) {
                        switch ($state) {
                            case 'one-day' : 
                                return 'Одноденний';
                            default:
                            return 'Регулярний';
                    }
                    })
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('count_teams')
                    ->label('Кіл. команд')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('count_rounds')
                    ->label('Кіл. турів')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('count_series')
                    ->label('Кіл. серій')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('count_matches')
                    ->label('Кіл. матчів')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('team_creator')
                    ->label('Хто створює команду')
                    ->formatStateUsing(function ($state) {
                        switch ($state) {
                            case 'admin' : 
                                return 'Адміністратор';
                            default:
                            return 'Гравець';
                    }
                    })
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Порядок')
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
            ->defaultSort('sort_order')
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
            'index' => Pages\ListTournaments::route('/'),
            'create' => Pages\CreateTournament::route('/create'),
            'view' => Pages\ViewTournament::route('/{record}'),
            'edit' => Pages\EditTournament::route('/{record}/edit'),
        ];
    }
}
