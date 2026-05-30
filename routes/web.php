<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\Admin\AiSettingsController;
use App\Http\Controllers\Admin\ContactSettingsController;
use App\Http\Controllers\Admin\DigitalSkillsItemController;
use App\Http\Controllers\Admin\HomeCtaController;
use App\Http\Controllers\Admin\ItIntakeController as AdminItIntakeController;
use App\Http\Controllers\Admin\JobVacancyController;
use App\Http\Controllers\Admin\JobApplicationController as AdminJobApplicationController;
use App\Http\Controllers\Admin\MailSettingsController;
use App\Http\Controllers\Admin\PaymentSettingsController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ServiceSubscriptionController;
use App\Http\Controllers\Admin\SiteSettingsController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\ServiceTypeController;
use App\Http\Controllers\Admin\GoogleReviewController;
use App\Http\Controllers\AboutPageController;
use App\Http\Controllers\ContactFormController;
use App\Http\Controllers\ContactPageController;
use App\Http\Controllers\DigitalSkillsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItIntakeController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\ProjectsPageController;
use App\Http\Controllers\Admin\DigitalSkillsEnrollmentController as AdminDigitalSkillsEnrollmentController;
use App\Http\Controllers\Instructor\AuthController as InstructorAuthController;
use App\Http\Controllers\Instructor\DashboardController as InstructorDashboardController;
use App\Http\Controllers\ServiceInquiryController;
use App\Http\Controllers\ServiceCheckoutController;
use App\Http\Controllers\ServicePortalController;
use App\Http\Controllers\ServicesPageController;
use App\Http\Controllers\TeamPageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [ServicesPageController::class, 'index'])->name('services');
Route::get('/services/{service}', [ServicesPageController::class, 'show'])->name('services.show');
Route::post('/services/apply', [ServiceInquiryController::class, 'store'])->name('services.apply');
Route::get('/services/checkout/{orderCode}', [ServiceCheckoutController::class, 'show'])->name('services.checkout');
Route::post('/services/checkout/{orderCode}/pay', [ServiceCheckoutController::class, 'pay'])->name('services.checkout.pay');
Route::get('/services/checkout/{orderCode}/paystack/callback', [ServiceCheckoutController::class, 'paystackCallback'])->name('services.checkout.paystack.callback');
Route::post('/services/checkout/{orderCode}/trial', [ServiceCheckoutController::class, 'startTrial'])->name('services.checkout.trial');
Route::post('/paystack/webhook', [ServiceCheckoutController::class, 'paystackWebhook'])->name('paystack.webhook');
Route::get('/service-portal', [ServicePortalController::class, 'showLogin'])->name('service-portal.login');
Route::post('/service-portal', [ServicePortalController::class, 'login'])->name('service-portal.login.submit');
Route::post('/service-portal/logout', [ServicePortalController::class, 'logout'])->name('service-portal.logout');
Route::get('/service-portal/dashboard', [ServicePortalController::class, 'dashboard'])->name('service-portal.dashboard');
Route::post('/service-portal/renew', [ServicePortalController::class, 'renew'])->name('service-portal.renew');
Route::get('/projects', [ProjectsPageController::class, 'index'])->name('projects');
Route::get('/team', [TeamPageController::class, 'index'])->name('team');
Route::get('/about', [AboutPageController::class, 'index'])->name('about');
Route::get('/contact', [ContactPageController::class, 'index'])->name('contact');
Route::post('/contact', [ContactFormController::class, 'store'])->name('contact.submit');
Route::get('/jobs', [JobsController::class, 'index'])->name('jobs.index');
Route::get('/jobs/apply', [JobsController::class, 'index'])->name('jobs.apply');
Route::get('/jobs/{vacancy}', [JobsController::class, 'show'])->name('jobs.show');
Route::get('/jobs/{vacancy}/apply', [JobsController::class, 'apply'])->name('jobs.vacancies.apply');
Route::post('/jobs/{vacancy}/apply', [JobsController::class, 'submit'])->name('jobs.vacancies.apply.submit');
Route::get('/it-intake', [ItIntakeController::class, 'create'])->name('it-intake');
Route::post('/it-intake', [ItIntakeController::class, 'store'])->name('it-intake.submit');
Route::get('/digital-skills', [DigitalSkillsController::class, 'index'])->name('digital-skills');
Route::get('/digital-skills/{item}', [DigitalSkillsController::class, 'show'])->whereNumber('item')->name('digital-skills.show');
Route::get('/digital-skills/{item}/lessons/{lesson}', [DigitalSkillsController::class, 'lesson'])->whereNumber('item')->whereNumber('lesson')->name('digital-skills.lessons.show');
Route::post('/digital-skills/{item}/rate', [DigitalSkillsController::class, 'rate'])->whereNumber('item')->name('digital-skills.rate');
Route::post('/digital-skills/enroll', [DigitalSkillsController::class, 'enroll'])->name('digital-skills.enroll');
Route::get('/digital-skills/checkout/{enrollment}', [DigitalSkillsController::class, 'checkout'])->name('digital-skills.checkout');
Route::post('/digital-skills/checkout/{enrollment}/pay', [DigitalSkillsController::class, 'pay'])->name('digital-skills.checkout.pay');
Route::post('/google-reviews', [HomeController::class, 'storeReview'])->name('google-reviews.store');

Route::prefix('instructor')->group(function () {
    Route::get('/login', [InstructorAuthController::class, 'showLogin'])->name('instructor.login');
    Route::post('/login', [InstructorAuthController::class, 'login'])->name('instructor.login.submit');
    Route::post('/logout', [InstructorAuthController::class, 'logout'])->name('instructor.logout');

    Route::middleware('instructor')->group(function () {
        Route::get('/dashboard', [InstructorDashboardController::class, 'index'])->name('instructor.dashboard');
    });
});
Route::get('/service-timeout', function () {
    $retry = request()->query('retry');
    $retryUrl = null;

    if (is_string($retry) && $retry !== '') {
        $appBase = rtrim(config('app.url'), '/');

        if (\Illuminate\Support\Str::startsWith($retry, $appBase)) {
            $retryUrl = $retry;
        } elseif (\Illuminate\Support\Str::startsWith($retry, '/')) {
            $retryUrl = url($retry);
        }
    }

    if (!$retryUrl) {
        $retryUrl = url()->previous();
    }

    return response()->view('errors.504', ['retryUrl' => $retryUrl], 504);
})->name('service-timeout');

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::middleware('admin')->group(function () {
        Route::get('/', function () {
            $activeJobs = \App\Models\JobVacancy::query()->where('is_active', true)->count();
            $totalJobs = \App\Models\JobVacancy::query()->count();

            $newJobApplications = \App\Models\JobApplication::query()->where('status', 'new')->count();
            $pendingIntakes = \App\Models\ItIntake::query()->where('approval_status', 'pending')->count();
            $newContactMessages = \App\Models\ContactMessage::query()->where('status', 'new')->count();

            $servicePaid = \App\Models\ServiceInquiry::query()->whereIn('payment_status', ['paid', 'free'])->count();
            $serviceActive = \App\Models\ServiceInquiry::query()->where('status', 'active')->count();
            $serviceExpiringSoon = \App\Models\ServiceInquiry::query()
                ->whereIn('payment_type', ['monthly', 'yearly'])
                ->whereNotNull('next_renewal_at')
                ->whereBetween('next_renewal_at', [now(), now()->copy()->addDays(7)])
                ->count();

            $stats = [
                'active_jobs' => $activeJobs,
                'total_jobs' => $totalJobs,
                'new_job_applications' => $newJobApplications,
                'pending_intakes' => $pendingIntakes,
                'new_contact_messages' => $newContactMessages,
                'service_paid' => $servicePaid,
                'service_active' => $serviceActive,
                'service_expiring_soon' => $serviceExpiringSoon,
                'notifications_total' => ($newJobApplications + $pendingIntakes + $newContactMessages),
            ];

            $start = now()->startOfDay()->subDays(6);
            $end = now()->endOfDay();

            $jobAppsByDate = \Illuminate\Support\Facades\DB::table('job_applications')
                ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
                ->whereBetween('created_at', [$start, $end])
                ->groupBy('d')
                ->pluck('c', 'd')
                ->toArray();

            $messagesByDate = \Illuminate\Support\Facades\DB::table('contact_messages')
                ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
                ->whereBetween('created_at', [$start, $end])
                ->groupBy('d')
                ->pluck('c', 'd')
                ->toArray();

            $intakesByDate = \Illuminate\Support\Facades\DB::table('it_intakes')
                ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
                ->whereBetween('created_at', [$start, $end])
                ->groupBy('d')
                ->pluck('c', 'd')
                ->toArray();

            $chartLabels = [];
            $chartApplications = [];
            $chartMessages = [];
            $chartIntakes = [];

            for ($i = 0; $i < 7; $i++) {
                $date = $start->copy()->addDays($i);
                $key = $date->format('Y-m-d');

                $chartLabels[] = $date->format('M j');
                $chartApplications[] = (int)($jobAppsByDate[$key] ?? 0);
                $chartMessages[] = (int)($messagesByDate[$key] ?? 0);
                $chartIntakes[] = (int)($intakesByDate[$key] ?? 0);
            }

            $chart = [
                'labels' => $chartLabels,
                'applications' => $chartApplications,
                'messages' => $chartMessages,
                'intakes' => $chartIntakes,
            ];

            $notifications = [];

            $recentApps = \App\Models\JobApplication::query()
                ->with('vacancy')
                ->where('status', 'new')
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();

            foreach ($recentApps as $app) {
                $notifications[] = [
                    'tone' => 'primary',
                    'title' => 'New job application',
                    'text' => ($app->full_name ?: 'Applicant') . ' — ' . ($app->vacancy?->title ?? $app->position ?? 'Job'),
                    'url' => route('admin.job-applications.show', $app),
                    'time' => $app->created_at,
                ];
            }

            $recentIntakes = \App\Models\ItIntake::query()
                ->where('approval_status', 'pending')
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();

            foreach ($recentIntakes as $intake) {
                $notifications[] = [
                    'tone' => 'warning',
                    'title' => 'IT intake awaiting approval',
                    'text' => ($intake->student_name ?: 'Student') . ' — ' . ($intake->institution ?: 'IMSU'),
                    'url' => route('admin.it-intakes.show', $intake),
                    'time' => $intake->created_at,
                ];
            }

            $recentMessages = \App\Models\ContactMessage::query()
                ->where('status', 'new')
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();

            foreach ($recentMessages as $msg) {
                $notifications[] = [
                    'tone' => 'success',
                    'title' => 'New contact message',
                    'text' => ($msg->name ?: 'Visitor') . ' — ' . ($msg->subject ?: 'No subject'),
                    'url' => route('admin.contact-messages.show', $msg),
                    'time' => $msg->created_at,
                ];
            }

            usort($notifications, function ($a, $b) {
                return ($b['time']?->timestamp ?? 0) <=> ($a['time']?->timestamp ?? 0);
            });

            $notifications = array_slice($notifications, 0, 10);

            return view('admin.dashboard', compact('stats', 'chart', 'notifications'));
        })->name('admin.dashboard');

        Route::resource('sliders', SliderController::class)->except(['show'])->names('admin.sliders');
        Route::post('sliders/video-chunk', [SliderController::class, 'uploadVideoChunk'])->name('admin.sliders.video-chunk');
        Route::post('sliders/video-complete', [SliderController::class, 'completeVideoUpload'])->name('admin.sliders.video-complete');
        Route::resource('service-types', ServiceTypeController::class)->except(['show'])->names('admin.service-types');
        Route::resource('services', ServiceController::class)->except(['show'])->names('admin.services');
        Route::get('services/{service}', [ServiceController::class, 'show'])->name('admin.services.show');
        Route::post('services/upload-image', [ServiceController::class, 'uploadEditorImage'])->name('admin.services.upload-image');
        Route::put('service-inquiries/{inquiry}/status', [ServiceController::class, 'updateInquiryStatus'])->name('admin.service-inquiries.status');
        Route::delete('service-inquiries/{inquiry}', [ServiceController::class, 'destroyInquiry'])->name('admin.service-inquiries.destroy');
        Route::get('service-subscriptions', [ServiceSubscriptionController::class, 'index'])->name('admin.service-subscriptions.index');
        Route::get('service-subscriptions/{inquiry}', [ServiceSubscriptionController::class, 'show'])->name('admin.service-subscriptions.show');
        Route::put('service-subscriptions/{inquiry}/progress', [ServiceSubscriptionController::class, 'updateProgress'])->name('admin.service-subscriptions.progress');
        Route::resource('projects', ProjectController::class)->except(['show'])->names('admin.projects');
        Route::resource('team', TeamMemberController::class)->except(['show'])->parameters(['team' => 'member'])->names('admin.team');

        Route::get('ctas', [HomeCtaController::class, 'index'])->name('admin.ctas.index');
        Route::get('ctas/{cta}/edit', [HomeCtaController::class, 'edit'])->name('admin.ctas.edit');
        Route::put('ctas/{cta}', [HomeCtaController::class, 'update'])->name('admin.ctas.update');

        Route::resource('digital-skills', DigitalSkillsItemController::class)
            ->parameters(['digital-skills' => 'item'])
            ->names('admin.digital-skills');
        Route::post('digital-skills/upload-media', [DigitalSkillsItemController::class, 'uploadEditorMedia'])->name('admin.digital-skills.upload-media');
        Route::post('digital-skills/{item}/lessons', [DigitalSkillsItemController::class, 'storeLesson'])->name('admin.digital-skills.lessons.store');
        Route::post('instructors', [DigitalSkillsItemController::class, 'storeInstructor'])->name('admin.instructors.store');
        Route::get('digital-skill-enrollments', [AdminDigitalSkillsEnrollmentController::class, 'index'])->name('admin.digital-skill-enrollments.index');
        Route::get('digital-skill-enrollments/{enrollment}', [AdminDigitalSkillsEnrollmentController::class, 'show'])->name('admin.digital-skill-enrollments.show');
        Route::put('digital-skill-enrollments/{enrollment}/status', [AdminDigitalSkillsEnrollmentController::class, 'updateStatus'])->name('admin.digital-skill-enrollments.status');

        Route::get('contact-settings', [ContactSettingsController::class, 'edit'])->name('admin.contact-settings.edit');
        Route::put('contact-settings', [ContactSettingsController::class, 'update'])->name('admin.contact-settings.update');

        Route::get('site-settings', [SiteSettingsController::class, 'edit'])->name('admin.site-settings.edit');
        Route::put('site-settings', [SiteSettingsController::class, 'update'])->name('admin.site-settings.update');

        Route::get('mail-settings', [MailSettingsController::class, 'edit'])->name('admin.mail-settings.edit');
        Route::put('mail-settings', [MailSettingsController::class, 'update'])->name('admin.mail-settings.update');

        Route::get('payment-settings', [PaymentSettingsController::class, 'edit'])->name('admin.payment-settings.edit');
        Route::put('payment-settings', [PaymentSettingsController::class, 'update'])->name('admin.payment-settings.update');

        Route::get('ai-settings', [AiSettingsController::class, 'index'])->name('admin.ai-settings.index');
        Route::get('ai-settings/edit', function() {
            return redirect()->route('admin.ai-settings.index');
        })->name('admin.ai-settings.edit');
        Route::post('ai-settings', [AiSettingsController::class, 'store'])->name('admin.ai-settings.store');
        Route::put('ai-settings/{ai_setting?}', [AiSettingsController::class, 'update'])->name('admin.ai-settings.update');
        Route::delete('ai-settings/{ai_setting}', [AiSettingsController::class, 'destroy'])->name('admin.ai-settings.destroy');
        Route::post('ai-settings/test', [AiSettingsController::class, 'testConnection'])->name('admin.ai-settings.test');

        Route::resource('jobs', JobVacancyController::class)->except(['show'])->names('admin.jobs');

        Route::get('job-applications', [AdminJobApplicationController::class, 'index'])->name('admin.job-applications.index');
        Route::get('job-applications/{application}', [AdminJobApplicationController::class, 'show'])->name('admin.job-applications.show');
        Route::put('job-applications/{application}/status', [AdminJobApplicationController::class, 'updateStatus'])->name('admin.job-applications.status');
        Route::get('job-applications/{application}/cv', [AdminJobApplicationController::class, 'downloadCv'])->name('admin.job-applications.cv');

        Route::get('it-intakes', [AdminItIntakeController::class, 'index'])->name('admin.it-intakes.index');
        Route::get('it-intakes/{intake}', [AdminItIntakeController::class, 'show'])->name('admin.it-intakes.show');
        Route::put('it-intakes/{intake}', [AdminItIntakeController::class, 'update'])->name('admin.it-intakes.update');
        Route::delete('it-intakes/{intake}', [AdminItIntakeController::class, 'destroy'])->name('admin.it-intakes.destroy');

        Route::get('contact-messages', [AdminContactMessageController::class, 'index'])->name('admin.contact-messages.index');
        Route::get('contact-messages/{message}', [AdminContactMessageController::class, 'show'])->name('admin.contact-messages.show');
        Route::put('contact-messages/{message}/status', [AdminContactMessageController::class, 'updateStatus'])->name('admin.contact-messages.status');
        Route::delete('contact-messages/{message}', [AdminContactMessageController::class, 'destroy'])->name('admin.contact-messages.destroy');

        Route::get('google-reviews', [GoogleReviewController::class, 'index'])->name('admin.google-reviews.index');
        Route::put('google-reviews/settings', [GoogleReviewController::class, 'updateSettings'])->name('admin.google-reviews.settings.update');
        Route::post('google-reviews/test-connection', [GoogleReviewController::class, 'testConnection'])->name('admin.google-reviews.test-connection');
        Route::put('google-reviews/{review}/approve', [GoogleReviewController::class, 'toggleApprove'])->name('admin.google-reviews.approve');
        Route::delete('google-reviews/{review}', [GoogleReviewController::class, 'destroy'])->name('admin.google-reviews.destroy');
    });
});

Route::get('/run-migrations-clear-cache-temp', function() {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $output = \Illuminate\Support\Facades\Artisan::output();
        
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        $output .= "\n" . \Illuminate\Support\Facades\Artisan::output();
        
        return response('<pre>' . $output . '</pre>');
    } catch (\Throwable $e) {
        return response('<pre>Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString() . '</pre>', 500);
    }
});

