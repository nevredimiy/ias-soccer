<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StadiumResource\Pages;
use App\Filament\Resources\StadiumResource\RelationManagers;
use App\Models\Stadium;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\ImageColumn;

class StadiumResource extends Resource
{
    protected static ?string $model = Stadium::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
     
    protected static ?string $navigationLabel = 'Стадіони';

    protected static ?string $navigationGroup = 'Локації';
    
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('location_id')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\FileUpload::make('photo')
                    ->image()
                    ->disk('public')
                    ->maxSize(2600)
                    ->directory('img/stadium')
                    ->deleteUploadedFileUsing(fn ($record) => 
                        $record->photo ? unlink(storage_path('app/public/' . $record->photo)) : null
                    )
                    ->columnSpan([
                        'sm' => 2,
                    ])
                    ->default('img/stadium/stadium_placeholder.png'),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('fields_40x20')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('fields_60x40')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('parking_spots')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('has_shower')
                    ->required(),
                Forms\Components\Toggle::make('has_speaker_system')
                    ->required(),
                Forms\Components\Toggle::make('has_wardrobe')
                    ->required(),
                Forms\Components\Toggle::make('has_toilet')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('location.address')
                    ->label('Локація')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),
                ImageColumn::make('photo')
                    ->disk('public')
                    ->height(50)
                    ->width(50)
                    ->label('Фото'), 
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fields_40x20')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fields_60x40')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('parking_spots')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('has_shower')
                    ->boolean(),
                Tables\Columns\IconColumn::make('has_speaker_system')
                    ->boolean(),
                Tables\Columns\IconColumn::make('has_wardrobe')
                    ->boolean(),
                Tables\Columns\IconColumn::make('has_toilet')
                    ->boolean(),
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
            'index' => Pages\ListStadia::route('/'),
            'create' => Pages\CreateStadium::route('/create'),
            'edit' => Pages\EditStadium::route('/{record}/edit'),
        ];
    }
}
