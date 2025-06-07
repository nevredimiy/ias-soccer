<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamResource\Pages;
use App\Filament\Resources\TeamResource\RelationManagers;
use App\Models\Team;
use App\Models\TeamColor;
use App\Models\User;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\ColorPicker;
use Filament\Tables\Columns\ColorColumn;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    
    protected static ?string $navigationLabel = 'Команди';
    
    protected static ?string $pluralModelLabel = "Команди";

    protected static ?string $navigationGroup = 'Дані матчів';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('owner_id')
                    ->options(function () {
                        return User::all()->mapWithKeys(function ($user) {
                            return [$user->id => "{$user->name} ({$user->email})"];
                        });
                    })
                    ->required()
                    ->searchable()
                    ->columnSpan(3),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->unique(
                        table: 'teams',
                        column: 'name',
                        ignorable: fn ($record) => $record, // для редактирования
                        modifyRuleUsing: function (Unique $rule, callable $get) {
                            return $rule->where('event_id', $get('event_id'));
                        }
                    )
                    ->maxLength(255),
                Forms\Components\Select::make('color_id')
                    ->label('Колір')
                    ->options(TeamColor::all()->pluck('name', 'id'))
                    ->required(),
                Forms\Components\Select::make('event_id')
                    ->label('Подія')
                    ->options(Event::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->columnSpan(3),

                Forms\Components\Select::make('status')
                    ->label('Статус')
                    ->options([
                        'awaiting_payment' => 'Очікування оплати',
                        'paid' => 'Сплачено',
                    ])
                    ->default('awaiting_payment')
                    ->required(),
                Forms\Components\FileUpload::make('group_photo')
                    ->image()
                    ->disk('public')
                    ->maxSize(2048)
                    ->directory('img/team_group_photo')
                    ->default('img/team_group_photo/group_placeholder.jpg')
                    ->deleteUploadedFileUsing(fn ($record) =>
                        $record->group_photo && file_exists(storage_path('app/public/' . $record->group_photo))
                            ? unlink(storage_path('app/public/' . $record->group_photo))
                            : null
                    ),

                Forms\Components\FileUpload::make('logo')
                    ->image()
                    ->disk('public')
                    ->maxSize(2048)
                    ->directory('img/team_logo')
                    ->default('img/team_logo/team_placeholder.png')
                    ->deleteUploadedFileUsing(fn ($record) =>
                        $record->logo && file_exists(storage_path('app/public/' . $record->logo))
                            ? unlink(storage_path('app/public/' . $record->logo))
                            : null
                    ),

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('owner.name')
                    ->label('Власник') 
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('logo')
                    ->disk('public')
                    ->label('Фото'), 
                Tables\Columns\ColorColumn::make('color.color_picker')
                    ->label('Колір'),  
                Tables\Columns\TextColumn::make('event_id')
                    ->numeric()
                    ->label('Подія'), 
                Tables\Columns\TextColumn::make('status')
                    ->label('Статус'), 
                Tables\Columns\ImageColumn::make('group_photo')
                    ->disk('public')
                    ->label('Групове фото'),
                
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
                Tables\Filters\SelectFilter::make('owner_id')
                    ->label('Власник')
                    ->relationship('owner', 'name')
                    ->searchable()
                    ->preload()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->before(function ($record) {
                    if ($record->logo && file_exists(storage_path('app/public/' . $record->logo))) {
                        unlink(storage_path('app/public/' . $record->logo));
                    }
                }),
                // Tables\Actions\Action::make('pay')
                //     ->label('Оплатить')
                //     ->color('primary')
                //     ->icon('heroicon-o-currency-dollar')
                //     ->action(fn (Team $record) => $record->processPayment()),
                
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
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }
}
