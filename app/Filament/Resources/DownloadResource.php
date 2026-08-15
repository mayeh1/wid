<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\AuthorizesViaPermissions;
use App\Filament\Resources\DownloadResource\Pages;
use App\Models\Download;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DownloadResource extends Resource
{
    use AuthorizesViaPermissions;

    protected static string $permissionGroup = 'content';

    protected static ?string $model = Download::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-down';

    protected static ?string $navigationGroup = 'Content';

    public const CATEGORIES = [
        'annual_report' => 'Annual Report',
        'financial_report' => 'Financial Report',
        'policy' => 'Policy',
        'governance_document' => 'Governance Document',
    ];

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')->required()->maxLength(255),
            Forms\Components\Select::make('category')->options(self::CATEGORIES)->required(),
            Forms\Components\TextInput::make('year')->numeric()->minValue(2000)->maxValue(2100),
            Forms\Components\Textarea::make('description')->columnSpanFull()->rows(2),
            SpatieMediaLibraryFileUpload::make('file')->collection('file')->acceptedFileTypes(['application/pdf'])->required(),
            Forms\Components\Toggle::make('is_published')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('year', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('category')->badge()->formatStateUsing(fn (string $state) => self::CATEGORIES[$state] ?? $state),
                Tables\Columns\TextColumn::make('year'),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')->options(self::CATEGORIES),
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
            'index' => Pages\ListDownloads::route('/'),
            'create' => Pages\CreateDownload::route('/create'),
            'edit' => Pages\EditDownload::route('/{record}/edit'),
        ];
    }
}
