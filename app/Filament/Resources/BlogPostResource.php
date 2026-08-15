<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogPostResource\Pages;
use App\Filament\Resources\BlogPostResource\RelationManagers\CommentsRelationManager;
use App\Models\BlogPost;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Blog & News';

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
                        Forms\Components\Select::make('blog_category_id')->relationship('category', 'name')->searchable(),
                        Forms\Components\Select::make('author_id')->relationship('author', 'name')->searchable(),
                        Forms\Components\TagsInput::make('tags')->columnSpanFull(),
                        Forms\Components\Textarea::make('excerpt')->columnSpanFull()->rows(2),
                        Forms\Components\RichEditor::make('body')->columnSpanFull(),
                        Forms\Components\DateTimePicker::make('published_at')->default(now()),
                        Forms\Components\Toggle::make('is_featured'),
                        Forms\Components\Toggle::make('is_published')->default(true),
                    ]),
                Forms\Components\Section::make('Media')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('featured_image')->collection('featured_image')->image(),
                    ]),
                Forms\Components\Section::make('SEO')
                    ->collapsed()
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')->maxLength(255),
                        Forms\Components\Textarea::make('meta_description')->rows(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('featured_image')->collection('featured_image'),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('category.name')->badge(),
                Tables\Columns\TextColumn::make('published_at')->dateTime()->sortable(),
                Tables\Columns\IconColumn::make('is_featured')->boolean(),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('blog_category_id')->relationship('category', 'name')->label('Category'),
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

    public static function getRelations(): array
    {
        return [
            CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }
}
