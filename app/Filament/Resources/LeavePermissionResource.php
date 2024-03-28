<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeavePermissionResource\Pages;
use App\Filament\Resources\LeavePermissionResource\RelationManagers;
use App\Models\LeavePermission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeavePermissionResource extends Resource
{

    public static function canViewAny(): bool
    {
        return auth()->user()->id==1;
    }
    protected static ?string $model = LeavePermission::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationGroup = 'Leave Details';

    protected static ?string $navigationLabel = 'Permissions';

    protected static ?int $navigationSort =4;

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
            'index' => Pages\ListLeavePermissions::route('/'),
            'create' => Pages\CreateLeavePermission::route('/create'),
            'view' => Pages\ViewLeavePermission::route('/{record}'),
            'edit' => Pages\EditLeavePermission::route('/{record}/edit'),
        ];
    }
}
