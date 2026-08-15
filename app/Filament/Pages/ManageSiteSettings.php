<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageSiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Site Settings';

    protected static ?string $navigationLabel = 'General Settings';

    protected static ?string $title = 'Site Settings';

    protected static string $view = 'filament.pages.manage-site-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(SiteSetting::current()->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Organization')
                            ->schema([
                                Forms\Components\TextInput::make('site_name')->required()->maxLength(255),
                                Forms\Components\TextInput::make('tagline')->maxLength(255),
                                Forms\Components\Textarea::make('mission_statement')->rows(3)->columnSpanFull(),
                                Forms\Components\TextInput::make('ein')->label('EIN'),
                                Forms\Components\TextInput::make('founder_name'),
                            ])->columns(2),
                        Forms\Components\Tabs\Tab::make('Contact')
                            ->schema([
                                Forms\Components\TextInput::make('contact_email')->email(),
                                Forms\Components\TextInput::make('contact_phone'),
                                Forms\Components\TextInput::make('address')->columnSpanFull(),
                                Forms\Components\TextInput::make('office_hours'),
                                Forms\Components\TextInput::make('google_maps_embed_url')->columnSpanFull(),
                            ])->columns(2),
                        Forms\Components\Tabs\Tab::make('Social Media')
                            ->schema([
                                Forms\Components\TextInput::make('facebook_url')->url(),
                                Forms\Components\TextInput::make('instagram_url')->url(),
                                Forms\Components\TextInput::make('linkedin_url')->url(),
                                Forms\Components\TextInput::make('twitter_url')->url(),
                                Forms\Components\TextInput::make('youtube_url')->url(),
                            ])->columns(2),
                        Forms\Components\Tabs\Tab::make('Impact Stats')
                            ->schema([
                                Forms\Components\TextInput::make('women_empowered_count')->numeric()->default(0),
                                Forms\Components\TextInput::make('scholarships_awarded_count')->numeric()->default(0),
                                Forms\Components\TextInput::make('communities_reached_count')->numeric()->default(0),
                                Forms\Components\TextInput::make('projects_completed_count')->numeric()->default(0),
                            ])->columns(2),
                        Forms\Components\Tabs\Tab::make('SEO')
                            ->schema([
                                Forms\Components\TextInput::make('meta_title')->maxLength(255),
                                Forms\Components\Textarea::make('meta_description')->rows(2),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        SiteSetting::current()->update($this->form->getState());

        Notification::make()
            ->title('Settings saved')
            ->success()
            ->send();
    }
}
