<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\AuthorizesViaPermissions;
use App\Filament\Resources\TeamMemberResource\Pages;
use App\Models\TeamMember;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Support\Str;

class TeamMemberResource extends Resource
{
    use AuthorizesViaPermissions;

    protected static string $permissionGroup = 'content';

    protected static ?string $model = TeamMember::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'About & Leadership';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('photo')
                            ->collection('photo')
                            ->image()
                            ->avatar()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $state, Forms\Set $set, Forms\Get $get) => $get('slug') === null && $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText('Used in this person\'s public profile URL.'),
                        Forms\Components\TextInput::make('role_title')->required()->maxLength(255),
                        Forms\Components\Select::make('category')
                            ->options([
                                'founder' => 'Founder',
                                'board_member' => 'Board Member',
                                'staff' => 'Staff',
                                'advisor' => 'Advisor',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('email')->email()->maxLength(255),
                        Forms\Components\Textarea::make('bio')->columnSpanFull()->rows(4),
                        Forms\Components\TextInput::make('order')->numeric()->default(0),
                        Forms\Components\Toggle::make('is_active')->default(true),
                    ]),
                Forms\Components\Section::make('Portfolio & Social Links')
                    ->description('Optional. Shown on the public site as clickable links on this member\'s bio.')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('website_url')->label('Website')->url()->maxLength(255),
                        Forms\Components\TextInput::make('portfolio_url')->label('Portfolio')->url()->maxLength(255),
                        Forms\Components\TextInput::make('linkedin_url')->label('LinkedIn')->url()->maxLength(255),
                        Forms\Components\TextInput::make('twitter_url')->label('Twitter / X')->url()->maxLength(255),
                        Forms\Components\TextInput::make('facebook_url')->label('Facebook')->url()->maxLength(255),
                        Forms\Components\TextInput::make('instagram_url')->label('Instagram')->url()->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('order')
            ->defaultSort('order')
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('photo')->collection('photo')->circular(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('role_title')->searchable(),
                Tables\Columns\TextColumn::make('category')->badge(),
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
            'index' => Pages\ListTeamMembers::route('/'),
            'create' => Pages\CreateTeamMember::route('/create'),
            'edit' => Pages\EditTeamMember::route('/{record}/edit'),
        ];
    }
}
