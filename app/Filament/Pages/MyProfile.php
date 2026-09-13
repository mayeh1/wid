<?php

namespace App\Filament\Pages;

use App\Models\TeamMember;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class MyProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationLabel = 'My Profile';

    protected static ?string $title = 'My Public Profile';

    protected static string $view = 'filament.pages.my-profile';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return static::teamMember() !== null;
    }

    public function mount(): void
    {
        abort_unless(static::canAccess(), 403);

        $this->form->fill(static::teamMember()->only([
            'bio', 'website_url', 'portfolio_url', 'linkedin_url', 'twitter_url', 'facebook_url', 'instagram_url',
        ]));
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->description('This is your own public bio shown on the About or Team page. You can\'t edit your name, title, category, or anyone else\'s profile here.')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('photo')
                            ->collection('photo')
                            ->image()
                            ->avatar()
                            ->model(static::teamMember())
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('bio')->rows(4)->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Portfolio & Social Links')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('website_url')->label('Website')->url()->maxLength(255),
                        Forms\Components\TextInput::make('portfolio_url')->label('Portfolio')->url()->maxLength(255),
                        Forms\Components\TextInput::make('linkedin_url')->label('LinkedIn')->url()->maxLength(255),
                        Forms\Components\TextInput::make('twitter_url')->label('Twitter / X')->url()->maxLength(255),
                        Forms\Components\TextInput::make('facebook_url')->label('Facebook')->url()->maxLength(255),
                        Forms\Components\TextInput::make('instagram_url')->label('Instagram')->url()->maxLength(255),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        static::teamMember()->update($this->form->getState());

        Notification::make()
            ->title('Profile updated')
            ->success()
            ->send();
    }

    protected static function teamMember(): ?TeamMember
    {
        $userId = auth()->id();

        if (! $userId) {
            return null;
        }

        return TeamMember::where('user_id', $userId)->first();
    }
}
