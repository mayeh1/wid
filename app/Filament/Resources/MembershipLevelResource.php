<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MembershipLevelResource\Pages;
use App\Models\MembershipLevel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class MembershipLevelResource extends Resource
{
    protected static ?string $model = MembershipLevel::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'Volunteers & Members';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(fn (string $state, Forms\Set $set, ?string $slug) => $slug === null && $set('slug', Str::slug($state))),
            Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(255),
            Forms\Components\TextInput::make('annual_price')->numeric()->prefix('$')->required(),
            Forms\Components\Textarea::make('description')->columnSpanFull()->rows(2),
            Forms\Components\TagsInput::make('perks')->columnSpanFull(),
            Forms\Components\TextInput::make('order')->numeric()->default(0),
            Forms\Components\Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('order')
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('annual_price')->money('usd'),
                Tables\Columns\TextColumn::make('memberships_count')->counts('memberships')->label('Members'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
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
            'index' => Pages\ListMembershipLevels::route('/'),
            'create' => Pages\CreateMembershipLevel::route('/create'),
            'edit' => Pages\EditMembershipLevel::route('/{record}/edit'),
        ];
    }
}
