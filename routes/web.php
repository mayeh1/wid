<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SuccessStoryController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\Webhooks\StripeWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/faqs', [FaqController::class, 'index'])->name('faqs');
Route::get('/resources', [DownloadController::class, 'index'])->name('resources');

Route::get('/programs', [ProgramController::class, 'index'])->name('programs.index');
Route::get('/programs/{slug}', [ProgramController::class, 'show'])->name('programs.show');

Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{slug}', [ProjectController::class, 'show'])->name('projects.show');

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');
Route::post('/events/{slug}/register', [EventController::class, 'register'])
    ->middleware('throttle:6,1')
    ->name('events.register');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/blog/{slug}/comment', [BlogController::class, 'comment'])
    ->middleware('throttle:6,1')
    ->name('blog.comment');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/gallery/{slug}', [GalleryController::class, 'show'])->name('gallery.show');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('contact.store');

Route::post('/newsletter', [NewsletterController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('newsletter.store');

Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/campaigns/{slug}', [CampaignController::class, 'show'])->name('campaigns.show');

Route::get('/donate', [DonationController::class, 'donate'])->name('donate');
Route::get('/donate/campaign/{campaign}', [DonationController::class, 'donate'])->name('donate.campaign');
Route::get('/donate/project/{project}', [DonationController::class, 'donate'])->name('donate.project');
Route::get('/donate/instructions/{receiptNumber}', [DonationController::class, 'instructions'])->name('donate.instructions');
Route::get('/donate/success/{receiptNumber}', [DonationController::class, 'success'])->name('donate.success');
Route::get('/donate/cancel/{receiptNumber}', [DonationController::class, 'cancel'])->name('donate.cancel');
Route::get('/donations/{receiptNumber}/receipt', [DonationController::class, 'receipt'])->name('donations.receipt');

Route::post('/webhooks/stripe', StripeWebhookController::class)->name('webhooks.stripe');

Route::get('/success-stories', [SuccessStoryController::class, 'index'])->name('success-stories.index');
Route::get('/success-stories/{slug}', [SuccessStoryController::class, 'show'])->name('success-stories.show');

Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');

Route::middleware('auth')->group(function () {
    Route::get('/volunteer', [VolunteerController::class, 'create'])->name('volunteer.create');
    Route::post('/volunteer', [VolunteerController::class, 'store'])->name('volunteer.store');
    Route::get('/volunteer/dashboard', [VolunteerController::class, 'dashboard'])->name('volunteer.dashboard');
    Route::get('/volunteer/certificate', [VolunteerController::class, 'certificate'])->name('volunteer.certificate');

    Route::get('/membership', [MembershipController::class, 'create'])->name('membership.create');
    Route::post('/membership', [MembershipController::class, 'store'])->name('membership.store');
    Route::get('/membership/dashboard', [MembershipController::class, 'dashboard'])->name('membership.dashboard');
    Route::post('/membership/renew', [MembershipController::class, 'renew'])->name('membership.renew');

    Route::get('/admin/reports/summary.pdf', [ReportController::class, 'summaryPdf'])->name('admin.reports.summary-pdf');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
