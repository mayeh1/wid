<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use Filament\Forms\Form;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationGroup = 'Site Settings';

    protected static ?string $navigationLabel = 'Activity Log';

    protected static ?string $modelLabel = 'activity log entry';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('description'),
            TextEntry::make('subject_type')->label('Subject'),
            TextEntry::make('causer.name')->label('Performed By'),
            TextEntry::make('created_at')->dateTime(),
            KeyValueEntry::make('properties')->label('Changes'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('description')->searchable(),
                Tables\Columns\TextColumn::make('subject_type')->label('Subject')->formatStateUsing(fn (?string $state) => $state ? class_basename($state) : '—'),
                Tables\Columns\TextColumn::make('causer.name')->label('Performed By')->default('System'),
                Tables\Columns\TextColumn::make('event')->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('subject_type')->options([
                    \App\Models\Donation::class => 'Donation',
                    \App\Models\User::class => 'User',
                    \App\Models\PaymentMethod::class => 'Payment Method',
                ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageActivities::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}
