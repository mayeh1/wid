<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\AuthorizesViaPermissions;
use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    use AuthorizesViaPermissions;

    protected static string $permissionGroup = 'products';

    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Products & Services';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Product Details')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', \Illuminate\Support\Str::slug($state ?? ''))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\Select::make('category')
                            ->options(Product::CATEGORIES)
                            ->required(),
                        Forms\Components\Select::make('type')
                            ->options(Product::TYPES)
                            ->default('digital')
                            ->required()
                            ->live(),
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->prefix('$')
                            ->required()
                            ->minValue(0),
                        Forms\Components\TextInput::make('compare_price')
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->helperText('Original price before discount (leave empty if no discount)'),
                        Forms\Components\TextInput::make('stock')
                            ->numeric()
                            ->minValue(0)
                            ->helperText('Leave empty for unlimited (digital products and services are always unlimited)')
                            ->visible(fn (Forms\Get $get) => $get('type') === 'physical'),
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                        Forms\Components\Toggle::make('is_published')
                            ->label('Published'),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured'),
                        Forms\Components\Textarea::make('short_description')
                            ->rows(2)
                            ->maxLength(500)
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('description')
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Media')
                    ->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                            ->collection('image')
                            ->image()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('4:3')
                            ->imageResizeTargetWidth('800')
                            ->imageResizeTargetHeight('600'),
                        Forms\Components\SpatieMediaLibraryFileUpload::make('download')
                            ->collection('download')
                            ->label('Downloadable File')
                            ->helperText('PDF, EPUB, ZIP, etc. — buyers get this after payment')
                            ->acceptedFileTypes(['application/pdf', 'application/epub+zip', 'application/zip', 'application/x-zip-compressed'])
                            ->maxSize(51200)
                            ->visible(fn (Forms\Get $get) => $get('type') === 'digital'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')
                    ->collection('image')
                    ->circular()
                    ->defaultImageUrl(fn () => 'https://ui-avatars.com/api/?name=P&background=7c3aed&color=fff'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->weight(FontWeight::SemiBold),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Product::CATEGORIES[$state] ?? $state),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Product::TYPES[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'digital' => 'info',
                        'service' => 'success',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('price')
                    ->money('usd')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->formatStateUsing(fn (?int $state, Product $record) => ($record->isDigital() || $record->isService()) ? '∞' : ($state ?? '∞')),
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean()
                    ->label('Published'),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Featured'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options(Product::CATEGORIES),
                Tables\Filters\SelectFilter::make('type')
                    ->options(Product::TYPES),
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Published'),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
