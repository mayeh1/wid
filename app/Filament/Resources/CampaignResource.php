<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CampaignResource\Pages;
use App\Models\Campaign;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CampaignResource extends Resource
{
    protected static ?string $model = Campaign::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Donations';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $state, Forms\Set $set, ?string $slug) => $slug === null && $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(255),
                        Forms\Components\TextInput::make('category')->maxLength(255),
                        Forms\Components\TextInput::make('target_amount')->numeric()->prefix('$')->required(),
                        Forms\Components\DatePicker::make('start_date'),
                        Forms\Components\DatePicker::make('end_date'),
                        Forms\Components\Textarea::make('excerpt')->columnSpanFull()->rows(2),
                        Forms\Components\RichEditor::make('description')->columnSpanFull(),
                        Forms\Components\Repeater::make('updates')
                            ->columnSpanFull()
                            ->schema([
                                Forms\Components\DatePicker::make('date')->required(),
                                Forms\Components\TextInput::make('text')->required(),
                            ])
                            ->columns(2)
                            ->defaultItems(0),
                        Forms\Components\Toggle::make('is_featured'),
                        Forms\Components\Toggle::make('is_published')->default(true),
                    ]),
                Forms\Components\Section::make('Media')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('featured_image')->collection('featured_image')->image(),
                        SpatieMediaLibraryFileUpload::make('gallery')->collection('gallery')->image()->multiple(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('featured_image')->collection('featured_image'),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('target_amount')->money('usd'),
                Tables\Columns\TextColumn::make('raised')->state(fn (Campaign $record) => '$'.number_format($record->raisedAmount(), 2))->label('Raised'),
                Tables\Columns\IconColumn::make('is_featured')->boolean(),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
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
            'index' => Pages\ListCampaigns::route('/'),
            'create' => Pages\CreateCampaign::route('/create'),
            'edit' => Pages\EditCampaign::route('/{record}/edit'),
        ];
    }
}
