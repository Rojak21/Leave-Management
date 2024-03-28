<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveResource\Pages;;
use App\Models\Leave;
use App\Models\leavepermission;
use App\Models\leavestatus;
use App\Models\leavetype;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class LeaveResource extends Resource
{
    protected static ?string $model = Leave::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $navigationGroup = 'Leave Details';

    protected static ?int $navigationSort =1;

    public static function getNavigationBadge(): ?string 
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        $user = Auth::user();

        // Fetch the leavepermission_id for 'Requested'
        $requestedPermissionId = Leavepermission::where('name', 'Requested')->value('id');

        $permissions = [
            $requestedPermissionId => 'Requested',
        ];
        
        // Check if the user has the 'super_admin' role
        if ($user->roles->where('name', 'super_admin')->isNotEmpty()) {
            // Fetch the leavepermission_id for 'Approved' and 'Declined'
            $approvedPermissionId = Leavepermission::where('name', 'Approved')->value('id');
            $declinedPermissionId = Leavepermission::where('name', 'Declined')->value('id');
        
            $permissions = [
                $approvedPermissionId => 'Approved',
                $requestedPermissionId => 'Requested',
                $declinedPermissionId => 'Declined',
            ];
        }
        return $form
            ->schema([
                Forms\Components\Section::make('Leave Details')
                ->schema([
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('date')
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->required(),
                Forms\Components\DatePicker::make('to_date')
                    ->native(false) ,
                // Forms\Components\TextInput::make('reason')
                //     ->maxLength(255)
                //     ->default(null),
                ])->columns(3),
                Forms\Components\Section::make('Leave Status')
                ->schema([
                Forms\Components\Select::make('leavetype_id')
                    ->label('Leave Type')
                    ->relationship('leavetype', 'name')
                    ->required(),
                Forms\Components\Select::make('leavestatus_id')
                    ->label('Leave Status')
                    ->relationship('leavestatus', 'name')
                    ->required(),
                Forms\Components\Select::make('leavepermission_id')
                    // ->badge()
                    // ->colors(['primary'])
                    ->label('Leave Permission')
                    ->options($permissions) 
                    ->required(),
                Forms\Components\Hidden::make('user_id')
                    ->default($user->id), 
                ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = Auth::user();
        $query = Leave::query();

        $userRoles = $user->roles->pluck('name')->toArray();

        if (in_array('super_admin', $userRoles)) {
            // Super admin can see all leaves
        } elseif (in_array('Employee', $userRoles)) {
            // Employee can only see their own leaves
            $query->where('user_id', $user->id);
        }
    

        // Define the options array for the leavepermission dropdown
        $leavePermission = leavepermission::pluck('name', 'id')->toArray();

        // Disable all statuses except 'requested' for employees
        if (in_array('Employee', $userRoles)) {
            foreach ($leavePermission as $key => $status) {
                if ($status !== 'Requested') {
                    $leavePermission[$key] = $status; 
                }
            }
        }

        $columns =[
            Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
            Tables\Columns\TextColumn::make('description')
                    ->searchable(),
            Tables\Columns\TextColumn::make('date')
                ->date()
                ->sortable(),
            Tables\Columns\TextColumn::make('to_date')
                ->date()
                ->sortable(),
            Tables\Columns\SelectColumn::make('leavetype_id')
                ->label('Leave Type')
                ->options(Leavetype::pluck('name', 'id')->toArray())
                ->sortable(),
            Tables\Columns\SelectColumn::make('leavestatus_id')
                ->label('Status')
                ->options(Leavestatus::pluck('name', 'id')->toArray())
                ->sortable(),
            // Tables\Columns\TextColumn::make('reason')
            //     ->searchable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];

        if (in_array('Employee', $userRoles)) {
            foreach ($columns as $key => $column) {
                if ($column->getName() === 'user.name') {
                    unset($columns[$key]); // Remove user.name column from the $columns array
                    break; // Stop the loop after removing the column
                }
            }
        }// Exclude user.name column for Employee role
        if (!in_array('Employee', $userRoles)) {
            $columns[] = Tables\Columns\TextColumn::make('user.name')
                ->numeric()
                ->sortable();
        }

        // Add user.name column for super_admin and manager roles
        if (in_array('super_admin', $userRoles) || in_array('manager', $userRoles)) {
            $columns[] = Tables\Columns\TextColumn::make('user.name')
                ->numeric()
                ->sortable();
        }

         // Exclude leavepermission dropdown for Employee role
         if (in_array('Employee', $userRoles)|| in_array('Manager', $userRoles)) {
            $columns[] = Tables\Columns\TextColumn::make('leavepermission.name')
                ->label('Leave Permission')
                ->searchable()
                ->sortable()
                ->badge()
                ->color(function ($record) {
                    switch ($record->leavepermission->name) {
                        case 'Approved':
                            return 'success'; // Green color for Approved
                        case 'Requested':
                            return 'warning'; // Yellow color for Requested
                        case 'Declined':
                            return 'danger'; // Red color for Declined
                        default:
                            return 'gray'; // Gray for other statuses
                        }
                    });
                }
            
        // Add leavepermission dropdown for super_admin role
        if (in_array('super_admin', $userRoles)) {
            $columns[] = Tables\Columns\SelectColumn::make('leavepermission_id')
                ->label('Leavepermission')
                ->options(Leavepermission::pluck('name', 'id')->toArray())
                ->sortable();
        }
        //  // Exclude leavepermission dropdown for Employee role
        //  if (in_array('Manager', $userRoles)) {
        //     $columns[] = Tables\Columns\TextColumn::make('leavepermission.name')
        //         ->label('Leave Permission')
        //         ->sortable();
        // }
    
        return $table
            ->query($query)
            ->columns($columns)
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListLeaves::route('/'),
            'create' => Pages\CreateLeave::route('/create'),
            'view' => Pages\ViewLeave::route('/{record}'),
            'edit' => Pages\EditLeave::route('/{record}/edit'),
        ];
    }
}
