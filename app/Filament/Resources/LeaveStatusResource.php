<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveStatusResource\Pages;
use App\Filament\Resources\LeaveStatusResource\RelationManagers;
use App\Models\LeaveStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeaveStatusResource extends Resource
{

    
    public static function canViewAny(): bool
    {
        return auth()->user()->id==1;
    }
    protected static ?string $model = LeaveStatus::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static ?string $navigationGroup = 'Leave Details';

    protected static ?string $navigationLabel = 'Status';

    protected static ?int $navigationSort =3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
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
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListLeaveStatuses::route('/'),
            'create' => Pages\CreateLeaveStatus::route('/create'),
            'view' => Pages\ViewLeaveStatus::route('/{record}'),
            'edit' => Pages\EditLeaveStatus::route('/{record}/edit'),
        ];
    }
}
