<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\AuthorizesViaPermissions;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    use AuthorizesViaPermissions;

    protected static string $permissionGroup = 'users';

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Site Settings';

    protected static ?string $navigationLabel = 'Users & Access';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')->required()->maxLength(255),
                        Forms\Components\TextInput::make('email')->email()->required()->maxLength(255),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->required()
                            ->default('pending'),
                        Forms\Components\Select::make('roles')
                            ->relationship(
                                'roles',
                                'name',
                                fn ($query) => auth()->user()?->hasRole('Super Admin')
                                    ? $query
                                    : $query->where('name', '!=', 'Super Admin'),
                            )
                            ->multiple()
                            ->preload()
                            ->helperText('Staff/admin roles. Leave empty for a regular site visitor account.'),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->minLength(8)
                            ->dehydrateStateUsing(fn (string $state) => Hash::make($state))
                            ->dehydrated(fn (?string $state) => filled($state))
                            ->required(fn (string $operation) => $operation === 'create')
                            ->helperText('Leave blank to keep the current password.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('roles.name')
                    ->badge()
                    ->separator(',')
                    ->placeholder('No roles (visitor)'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->label('Signed Up'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                ]),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (User $record) => static::userCan(['manage']) && ! $record->isApproved())
                    ->requiresConfirmation()
                    ->action(function (User $record) {
                        $record->update(['status' => 'approved']);
                        Notification::make()->title('User approved')->success()->send();
                    }),
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (User $record) => static::userCan(['manage']) && ! $record->isRejected())
                    ->requiresConfirmation()
                    ->action(function (User $record) {
                        $record->update(['status' => 'rejected']);
                        Notification::make()->title('User rejected')->warning()->send();
                    }),
                Tables\Actions\EditAction::make()->visible(fn (User $record) => static::canEdit($record)),
                Tables\Actions\DeleteAction::make()->visible(fn (User $record) => static::canDelete($record)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function canEdit($record): bool
    {
        if ($record->hasRole('Super Admin') && ! auth()->user()?->hasRole('Super Admin')) {
            return false;
        }

        return static::userCan(['update', 'manage']);
    }

    public static function canDelete($record): bool
    {
        if ($record->hasRole('Super Admin') && ! auth()->user()?->hasRole('Super Admin')) {
            return false;
        }

        if ($record->is(auth()->user())) {
            return false;
        }

        return static::userCan(['delete', 'manage']);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
