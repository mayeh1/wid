<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Programs & Projects';

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
                        Forms\Components\Select::make('status')
                            ->options(['upcoming' => 'Upcoming', 'current' => 'Current', 'completed' => 'Completed'])
                            ->required()
                            ->default('upcoming'),
                        Forms\Components\TextInput::make('location')->maxLength(255),
                        Forms\Components\DatePicker::make('start_date'),
                        Forms\Components\DatePicker::make('end_date'),
                        Forms\Components\TextInput::make('budget')->numeric()->prefix('$'),
                        Forms\Components\TextInput::make('raised')->numeric()->prefix('$'),
                        Forms\Components\TextInput::make('progress_percent')->numeric()->suffix('%')->minValue(0)->maxValue(100),
                        Forms\Components\Textarea::make('excerpt')->columnSpanFull()->rows(2),
                        Forms\Components\RichEditor::make('description')->columnSpanFull(),
                        Forms\Components\Repeater::make('timeline')
                            ->columnSpanFull()
                            ->schema([
                                Forms\Components\TextInput::make('date')->required(),
                                Forms\Components\TextInput::make('label')->required(),
                            ])
                            ->columns(2)
                            ->defaultItems(0),
                        Forms\Components\Toggle::make('is_featured'),
                        Forms\Components\Toggle::make('is_published')->default(true),
                    ]),
                Forms\Components\Section::make('Media')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('featured_image')->collection('featured_image')->image(),
                        SpatieMediaLibraryFileUpload::make('gallery')->collection('gallery')->image()->multiple()->reorderable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('featured_image')->collection('featured_image'),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn (string $state) => match ($state) {
                    'completed' => 'success',
                    'current' => 'warning',
                    default => 'gray',
                }),
                Tables\Columns\TextColumn::make('progress_percent')->suffix('%'),
                Tables\Columns\TextColumn::make('budget')->money('usd'),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(['upcoming' => 'Upcoming', 'current' => 'Current', 'completed' => 'Completed']),
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
