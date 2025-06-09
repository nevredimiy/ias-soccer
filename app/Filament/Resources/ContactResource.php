<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Filament\Resources\ContactResource\RelationManagers;
use App\Models\Contact;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('photo')
                    ->image()
                    ->disk('public')
                    ->maxSize(2600)
                    ->directory('img/contacts')
                    ->deleteUploadedFileUsing(fn ($record) => 
                        $record->photo ? unlink(storage_path('app/public/' . $record->photo)) : null
                    )
                     ->columnSpan('full'),
                 TextInput::make('full_name')
                    ->required()
                    ->maxLength(255)
                    ->label('Повне ім\'я'),
                 TextInput::make('position')
                    ->required()
                    ->maxLength(255)
                    ->label('Посада'),
                PhoneInput::make('phone')
                    ->validateFor(
                        country: 'UA', // default: 'AUTO'
                        lenient: true, // default: false
                    )
                    ->onlyCountries(['ua'])
                    ->label('Телефон'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                ImageColumn::make('photo')
                    ->disk('public')
                    ->height(50)
                    ->width(50)
                    ->label('Фото')
                    ->circular()
                    ->defaultImageUrl(url('/img/contacts/default_avatar.jpg')),
                TextColumn::make('full_name')
                    ->label('Повне ім\'я')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('position')
                    ->label('Посада')
                    ->sortable()
                    ->searchable(),
                 TextColumn::make('phone')
                    ->label('Телефон') 
                    ->sortable()
                    ->searchable(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageContacts::route('/'),
        ];
    }
}
