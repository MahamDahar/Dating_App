<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AdminPaymentController;
use App\Http\Controllers\AdminBlockedUserController;
use App\Http\Controllers\AdminContentModerationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminFaqController;
use App\Http\Controllers\AdminLegalController;
use App\Http\Controllers\AdminManagementController;
use App\Http\Controllers\AdminMatchController;
use App\Http\Controllers\AdminMessageController;
use App\Http\Controllers\AdminNotificationsController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\AdminProposalController;
use App\Http\Controllers\AdminSupportTicketController;
use App\Http\Controllers\AdminSubscriptionController;
use App\Http\Controllers\AdminSubscriptionPlanController;
use App\Http\Controllers\AdminPhotoVerificationController;
use App\Http\Controllers\AdminVerificationController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlockedUsersController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatRequestController;
use App\Http\Controllers\ChatWebRtcController;
use App\Http\Controllers\DiscoverController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\MarriageController;
use App\Http\Controllers\NotificationSettingsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PremiumController;
use App\Http\Controllers\PrivacySettingController;
use App\Http\Controllers\ProfileVisibilityController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\SavedProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PhotoVerificationController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\WhoViewedMeController;
use Illuminate\Support\Facades\Route;

// -----------------------------------------------
// Auth Routes (Guest only)
// -----------------------------------------------
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('guest')->name('password.update');
Route::get('/verify-email-otp', [AuthController::class, 'showOtpVerificationForm'])->middleware('guest')->name('verification.otp.show');
Route::post('/verify-email-otp', [AuthController::class, 'verifyOtp'])->middleware('guest')->name('verification.otp.verify');
Route::post('/verify-email-otp/resend', [AuthController::class, 'resendOtp'])->middleware('guest')->name('verification.otp.resend');

Route::get('/register', [AuthController::class, 'show'])->name('frontend.register.show');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// -----------------------------------------------
// Frontend / Public Routes
// -----------------------------------------------
Route::get('/', [HomeController::class, 'index'])->name('frontend.index');
Route::get('/membership', [HomeController::class, 'membership'])->name('frontend.membership');
Route::get('/community', [HomeController::class, 'community'])->name('frontend.community');
Route::get('/blogs', [HomeController::class, 'blogs'])->name('frontend.blogs');
Route::get('/contact', [HomeController::class, 'contact'])->name('frontend.contact');
Route::get('/about', [HomeController::class, 'about'])->name('frontend.about');

// -----------------------------------------------
// Logout (shared)
// -----------------------------------------------
Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

// -----------------------------------------------
// Return to Admin — middleware ke BAHAR (auth only)
// Kyunki jab admin user ke account mein hota hai
// toh 'admin' middleware block kar deta tha
// -----------------------------------------------
Route::get('/admin/return-to-admin', [AdminController::class, 'returnToAdmin'])
    ->middleware('auth')
    ->name('admin.return');

// -----------------------------------------------
// Google OAuth
// -----------------------------------------------
Route::get('/auth/google', [GoogleController::class, 'redirect']);
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

// -----------------------------------------------
// Admin Routes
// -----------------------------------------------
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');

    Route::get('/profile/edit', [AdminController::class, 'editOwn'])->name('edit');
    Route::put('/profile/update', [AdminController::class, 'updateOwn'])->name('update');

    // User list & delete
    Route::get('/userdetail', [AdminController::class, 'userList'])->name('index');
    Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('users.destroy');

    // Login as user (return route ab bahar hai)
    Route::get('/users/{id}/login-as', [AdminController::class, 'loginAsUser'])->name('users.login-as');

    // All user profiles list
    Route::get('/profiles', [UserProfileController::class, 'index'])->name('profiles.index');

});

// -----------------------------------------------
// User Routes
// -----------------------------------------------
Route::prefix('user')->name('user.')->middleware(['auth', 'user'])->group(function () {

    // Profile
    Route::get('/profile',            [UserProfileController::class, 'show'])->name('profile');
    Route::get('/profile/view/{user}', [UserProfileController::class, 'view'])->name('profile.view');
    Route::post('/profile/store',     [UserProfileController::class, 'store'])->name('profile.store');
    Route::patch('/profile/update',   [UserProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/destroy', [UserProfileController::class, 'destroy'])->name('profile.destroy');

    // Photo routes
    Route::post('/profile/photo/upload', [UserProfileController::class, 'uploadPhoto'])->name('profile.photo.upload');
    Route::post('/profile/photo/main',   [UserProfileController::class, 'setMainPhoto'])->name('profile.photo.main');
    Route::post('/profile/photo/blur',   [UserProfileController::class, 'toggleBlurPhoto'])->name('profile.photo.blur');
    Route::delete('/profile/photo',      [UserProfileController::class, 'deletePhoto'])->name('profile.photo.delete');

    // Discover
    Route::get('/discover', [DiscoverController::class, 'index'])->name('discover');

    // Chat (static path segments before /chat/{user} so they are not treated as user ids)
    Route::get('/chat',              [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/sidebar/poll', [ChatController::class, 'pollSidebar'])->name('chat.sidebar.poll');
    Route::post('/chat/{user}/media', [ChatController::class, 'sendMedia'])->name('chat.media');
    Route::post('/chat/{user}/messages/delete', [ChatController::class, 'deleteMessages'])->name('chat.messages.delete');
    Route::post('/chat/{user}/read-all', [ChatController::class, 'markAllRead'])->name('chat.read-all');
    Route::get('/chat/{user}/messages/poll', [ChatController::class, 'pollMessages'])->name('chat.messages.poll');
    Route::get('/chat/{user}/starred', [ChatController::class, 'starredMessages'])->name('chat.starred');
    Route::post('/chat/{user}/messages/star', [ChatController::class, 'starMessages'])->name('chat.messages.star');
    Route::get('/chat/{user}/call',  [ChatWebRtcController::class, 'show'])->name('chat.call');
    Route::get('/chat/{user}/signals', [ChatWebRtcController::class, 'poll'])->name('chat.signals.poll');
    Route::post('/chat/{user}/signals', [ChatWebRtcController::class, 'store'])->name('chat.signals.store');
    Route::get('/chat/{user}',       [ChatController::class, 'index'])->name('chat.with');
    Route::post('/chat/{user}',      [ChatController::class, 'send'])->name('chat.send');

    // Premium
    Route::get('/premium/plans',       [PremiumController::class, 'index'])->name('premium.plans');
    Route::post('/premium/checkout',   [PremiumController::class, 'checkout'])->name('premium.checkout');
    Route::get('/premium/success',     [PremiumController::class, 'success'])->name('premium.success');
    Route::get('/premium/cancel',      [PremiumController::class, 'cancel'])->name('premium.cancel');
    Route::post('/stripe/webhook',     [PremiumController::class, 'webhook'])->name('stripe.webhook');

    // Photo Verification (manual now; live face later)
    Route::get('/verification/photo', [PhotoVerificationController::class, 'index'])->name('verification.index');
    Route::post('/verification/photo', [PhotoVerificationController::class, 'store'])->name('verification.store');
});

// -----------------------------------------------
// Settings Routes
// -----------------------------------------------
Route::prefix('setting')->name('user.')->middleware(['auth', 'user'])->group(function () {
    Route::get('/profile',       [SettingsController::class, 'profile'])->name('settings.profile');
    Route::get('/privacy',       [SettingsController::class, 'privacy'])->name('settings.privacy');
    Route::get('/notifications', [SettingsController::class, 'notifications'])->name('settings.notifications');
    Route::get('/password',      [SettingsController::class, 'password'])->name('settings.password');
    Route::get('/deactivate',    [SettingsController::class, 'confirmdeactivate'])->name('settings.confirmdeactivate');
    Route::get('/deactivate',    [SettingsController::class, 'deactivate'])->name('settings.deactivate');
    Route::get('/delete',        [SettingsController::class, 'delete'])->name('settings.delete');
    Route::post('/destroy',      [SettingsController::class, 'destroy'])->name('settings.destroy');
    Route::get('/logout',        [SettingsController::class, 'logout'])->name('settings.logout');
    Route::put('/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
});

// -----------------------------------------------
// Who Viewed Me
// -----------------------------------------------
Route::get('/who-viewed-me', [WhoViewedMeController::class, 'index'])->name('user.who-viewed-me');

// -----------------------------------------------
// Notification Settings
// -----------------------------------------------
Route::middleware(['auth'])->prefix('user/settings')->name('user.settings.')->group(function () {

    Route::get('/notifications', [NotificationSettingsController::class, 'index'])
        ->name('notifications');

    Route::get('/notifications/push',    [NotificationSettingsController::class, 'push'])
        ->name('notifications.push');
    Route::put('/notifications/push',    [NotificationSettingsController::class, 'updatePush'])
        ->name('notifications.push.update');

    Route::get('/notifications/in-app',  [NotificationSettingsController::class, 'inApp'])
        ->name('notifications.inapp');
    Route::put('/notifications/in-app',  [NotificationSettingsController::class, 'updateInApp'])
        ->name('notifications.inapp.update');

    Route::get('/notifications/email',   [NotificationSettingsController::class, 'email'])
        ->name('notifications.email');
    Route::put('/notifications/email',   [NotificationSettingsController::class, 'updateEmail'])
        ->name('notifications.email.update');

    Route::get('/notifications/security', [NotificationSettingsController::class, 'security'])
        ->name('notifications.security');
    Route::put('/notifications/security', [NotificationSettingsController::class, 'updateSecurity'])
        ->name('notifications.security.update');
});

// -----------------------------------------------
// Profile Visibility
// -----------------------------------------------
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/settings/visibility', [ProfileVisibilityController::class, 'show'])
        ->name('settings.visibility');
    Route::put('/settings/visibility', [ProfileVisibilityController::class, 'update'])
        ->name('settings.visibility.update');
});

// -----------------------------------------------
// Activity
// -----------------------------------------------
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/activity', [ActivityController::class, 'index'])->name('activity');
});

// -----------------------------------------------
// Blocked Users
// -----------------------------------------------
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/blocked',         [BlockedUsersController::class, 'index'])->name('blocked.index');
    Route::post('/blocked/{id}',   [BlockedUsersController::class, 'block'])->name('blocked.block');
    Route::delete('/blocked/{id}', [BlockedUsersController::class, 'unblock'])->name('blocked.unblock');
});

// -----------------------------------------------
// Marriage
// -----------------------------------------------
Route::prefix('user/marriage')->name('user.marriage.')->group(function () {
    Route::get('/',           [MarriageController::class, 'index'])->name('index');
    Route::get('/nearby',     [MarriageController::class, 'nearbyProfiles'])->name('nearby');
    Route::post('/location',  [MarriageController::class, 'updateLocation'])->name('location');
    Route::post('/like',      [MarriageController::class, 'like'])->name('like');
    Route::post('/pass',      [MarriageController::class, 'pass'])->name('pass');
    Route::post('/favourite', [MarriageController::class, 'toggleFavourite'])->name('favourite');
});

Route::prefix('admin/')->name('admin.reports.')->group(function () {
    Route::get('/',   [ReportsController::class, 'index'])->name('index');
});
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
 
    // ... existing routes ...
 
    // Notifications
    Route::get('/notifications',            [AdminNotificationsController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all',  [AdminNotificationsController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/{id}/read', [AdminNotificationsController::class, 'markRead'])->name('notifications.mark-read');
    Route::delete('/notifications/{id}',    [AdminNotificationsController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications',         [AdminNotificationsController::class, 'clearAll'])->name('notifications.clear');
 
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
 
    // ... existing routes ...
 
    // Admin Management
    Route::get('/management',                    [AdminManagementController::class, 'index'])->name('management.index');
    Route::get('/management/role/{id}/edit',     [AdminManagementController::class, 'editRole'])->name('management.edit-role');
    Route::put('/management/role/{id}',          [AdminManagementController::class, 'updateRole'])->name('management.update-role');
    Route::post('/management/assign-role',       [AdminManagementController::class, 'assignRole'])->name('management.assign-role');
    Route::post('/management/create',            [AdminManagementController::class, 'createAdmin'])->name('management.create');
    Route::delete('/management/{id}/remove',     [AdminManagementController::class, 'removeAdmin'])->name('management.remove');
 
});

Route::get('/requests',           [RequestController::class, 'index'])->name('user.requests.index');
Route::get('/proposals/sent',     [ProposalController::class, 'sent'])->name('user.proposals.sent');
Route::get('/proposals/received', [ProposalController::class, 'received'])->name('user.proposals.received');
Route::get('/help',               [HelpController::class, 'index'])->name('user.help');
Route::get('/report-problem',     [ReportsController::class, 'problem'])->name('user.report.problem');
Route::post('/report-problem', [ReportsController::class, 'storeProblem'])->name('user.report.problem.store');
Route::post('/report-user', [ReportsController::class, 'store'])->middleware('auth')->name('user.report.store');
Route::get('/privacy-policy',     [PageController::class, 'privacy'])->name('user.privacy.policy');
  

Route::prefix('faqs')->group(function () {
    Route::get('/',           [AdminFaqController::class, 'index'])->name('admin.faqs.index');
    Route::get('/create',     [AdminFaqController::class, 'create'])->name('admin.faqs.create');
    Route::post('/',          [AdminFaqController::class, 'store'])->name('admin.faqs.store');
    Route::get('/{faq}/edit', [AdminFaqController::class, 'edit'])->name('admin.faqs.edit');
    Route::put('/{faq}',      [AdminFaqController::class, 'update'])->name('admin.faqs.update');
    Route::delete('/{faq}',   [AdminFaqController::class, 'destroy'])->name('admin.faqs.destroy');
    Route::post('/{faq}/toggle', [AdminFaqController::class, 'toggle'])->name('admin.faqs.toggle');
});

// Admin routes (inside admin middleware group)
Route::get('/legal',        [AdminLegalController::class, 'index'])->name('admin.legal.index');
Route::post('/legal/{type}',[AdminLegalController::class, 'update'])->name('admin.legal.update');

// User route
Route::get('/privacy-policy', [LegalController::class, 'index'])->name('user.privacy.policy');

// User Proposal Routes
Route::prefix('proposals')->group(function () {
    Route::get('/sent',                [ProposalController::class, 'sent'])->name('user.proposals.sent');
    Route::get('/received',            [ProposalController::class, 'received'])->name('user.proposals.received');
    Route::post('/send/{user}',        [ProposalController::class, 'send'])->name('user.proposals.send');
    Route::post('/{proposal}/accept',  [ProposalController::class, 'accept'])->name('user.proposals.accept');
    Route::post('/{proposal}/reject',  [ProposalController::class, 'reject'])->name('user.proposals.reject');
    Route::post('/{proposal}/block',   [ProposalController::class, 'block'])->name('user.proposals.block');
});

// After proposal is accepted: chat request → then messaging (match_requests.accepted)
Route::prefix('chat-requests')->group(function () {
    Route::post('/{chatRequest}/accept', [ChatRequestController::class, 'accept'])->name('user.chat-requests.accept');
    Route::post('/{chatRequest}/reject', [ChatRequestController::class, 'reject'])->name('user.chat-requests.reject');
    Route::post('/{user}', [ChatRequestController::class, 'store'])->name('user.chat-requests.store');
});

Route::get('/settings/privacy',  [PrivacySettingController::class, 'index'])->name('user.settings.privacy');
Route::post('/settings/privacy', [PrivacySettingController::class, 'update'])->name('user.settings.privacy.update');

Route::get('/likes',          [LikeController::class, 'index'])->name('user.likes.index');
Route::post('/likes/{user}',  [LikeController::class, 'toggle'])->name('user.likes.toggle');

Route::prefix('user')->name('user.')->middleware(['auth', 'user'])->group(function () {
 
    // ... existing routes ...
 
    // Saved Profiles
    Route::get('/saved-profiles',        [SavedProfileController::class, 'index'])->name('saved.index');
    Route::post('/saved-profiles/toggle',[SavedProfileController::class, 'toggle'])->name('saved.toggle');
    Route::post('/saved-profiles/save',  [SavedProfileController::class, 'save'])->name('saved.save');
    Route::post('/saved-profiles/unsave',[SavedProfileController::class, 'unsave'])->name('saved.unsave');
 
});


Route::get('/verification',              [AdminVerificationController::class, 'index'])->name('admin.verification.index');
Route::post('/verification/{user}/verify',  [AdminVerificationController::class, 'verify'])->name('admin.verification.verify');
Route::post('/verification/{user}/unverify',[AdminVerificationController::class, 'unverify'])->name('admin.verification.unverify');
Route::post('/verification/send-reminder',  [AdminVerificationController::class, 'sendReminder'])->name('admin.verification.reminder');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/photo-verifications', [AdminPhotoVerificationController::class, 'index'])->name('photo_verifications.index');
    Route::post('/photo-verifications/{verification}/approve', [AdminPhotoVerificationController::class, 'approve'])->name('photo_verifications.approve');
    Route::post('/photo-verifications/{verification}/reject', [AdminPhotoVerificationController::class, 'reject'])->name('photo_verifications.reject');
});

Route::get('/matches', [AdminMatchController::class, 'index'])->name('admin.matches.index');

Route::get('/messages',             [AdminMessageController::class, 'index'])->name('admin.messages.index');
Route::get('/messages/{u1}/{u2}',   [AdminMessageController::class, 'show'])->name('admin.messages.show');

Route::get('/problem-reports',              [AdminReportController::class, 'index'])->name('admin.problem_reports.index');
Route::get('/problem-reports/{report}',     [AdminReportController::class, 'show'])->name('admin.problem_reports.show');
Route::post('/problem-reports/{report}/dismiss', [AdminReportController::class, 'dismiss'])->name('admin.problem_reports.dismiss');
Route::post('/problem-reports/users/{user}/block',   [AdminReportController::class, 'blockUser'])->name('admin.problem_reports.block');
Route::post('/problem-reports/users/{user}/unblock', [AdminReportController::class, 'unblockUser'])->name('admin.problem_reports.unblock');
Route::post('/problem-reports/users/{user}/warn',    [AdminReportController::class, 'sendWarning'])->name('admin.problem_reports.warn');

Route::get('/blocked-users',                  [AdminBlockedUserController::class, 'index'])->name('admin.blocked_users.index');
Route::post('/blocked-users/{user}/block',    [AdminBlockedUserController::class, 'block'])->name('admin.blocked_users.block');
Route::post('/blocked-users/{user}/unblock',  [AdminBlockedUserController::class, 'unblock'])->name('admin.blocked_users.unblock');

// Search for manual block modal
Route::get('/blocked-users/search',           [AdminBlockedUserController::class, 'search'])->name('admin.blocked_users.search');
Route::post('/blocked-users/block-search',    [AdminBlockedUserController::class, 'block'])->name('admin.blocked_users.block.search');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
 
    // ... existing routes ...
 
    // ── Analytics ──
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
 
    // ── Marketing ──
    Route::get('/marketing',                                    [MarketingController::class, 'index'])->name('marketing.index');
    Route::post('/marketing/send-email',                        [MarketingController::class, 'sendEmail'])->name('marketing.send-email');
    Route::post('/marketing/send-push',                         [MarketingController::class, 'sendPush'])->name('marketing.send-push');
    Route::post('/marketing/promo',                             [MarketingController::class, 'storePromo'])->name('marketing.store-promo');
    Route::patch('/marketing/promo/{id}/toggle',                [MarketingController::class, 'togglePromo'])->name('marketing.toggle-promo');
    Route::delete('/marketing/promo/{id}',                      [MarketingController::class, 'deletePromo'])->name('marketing.delete-promo');
    Route::post('/marketing/announcement',                      [MarketingController::class, 'storeAnnouncement'])->name('marketing.store-announcement');
    Route::patch('/marketing/announcement/{id}/toggle',         [MarketingController::class, 'toggleAnnouncement'])->name('marketing.toggle-announcement');
    Route::delete('/marketing/announcement/{id}',               [MarketingController::class, 'deleteAnnouncement'])->name('marketing.delete-announcement');
 
});

Route::get('/proposals',             [AdminProposalController::class, 'index'])->name('admin.proposals.index');
Route::delete('/proposals/{proposal}', [AdminProposalController::class, 'destroy'])->name('admin.proposals.destroy');

// Content Moderation
Route::get('/content-moderation',              [AdminContentModerationController::class, 'index'])->name('admin.content.index');
Route::post('/content-moderation/{flag}/approve', [AdminContentModerationController::class, 'approve'])->name('admin.content.approve');
Route::post('/content-moderation/{flag}/reject',  [AdminContentModerationController::class, 'reject'])->name('admin.content.reject');

// Support Tickets
Route::get('/support-tickets',              [AdminSupportTicketController::class, 'index'])->name('admin.support_tickets.index');
Route::get('/support-tickets/{ticket}',     [AdminSupportTicketController::class, 'show'])->name('admin.support_tickets.show');
Route::post('/support-tickets/{ticket}/reply', [AdminSupportTicketController::class, 'reply'])->name('admin.support_tickets.reply');
Route::delete('/support-tickets/{ticket}',  [AdminSupportTicketController::class, 'destroy'])->name('admin.support_tickets.destroy');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
 
    // ... existing routes ...
 
    // Payments
    Route::get('/payments',              [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/export',       [AdminPaymentController::class, 'export'])->name('payments.export');
    Route::get('/payments/{id}',         [AdminPaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/manual',      [AdminPaymentController::class, 'storeManual'])->name('payments.store-manual');

    // ── Subscriptions (Admin) ──
    Route::get('/subscriptions',                          [AdminSubscriptionController::class, 'index'])
        ->name('subscriptions.index');
    Route::get('/subscriptions/users/{user}',           [AdminSubscriptionController::class, 'show'])
        ->name('subscriptions.users.show');
    Route::post('/subscriptions/users/{user}/activate', [AdminSubscriptionController::class, 'activate'])
        ->name('subscriptions.users.activate');
    Route::post('/subscriptions/users/{user}/cancel',   [AdminSubscriptionController::class, 'cancel'])
        ->name('subscriptions.users.cancel');

    // Plans CRUD
    Route::get('/subscriptions/plans',                     [AdminSubscriptionPlanController::class, 'index'])
        ->name('subscriptions.plans.index');
    Route::get('/subscriptions/plans/create',             [AdminSubscriptionPlanController::class, 'create'])
        ->name('subscriptions.plans.create');
    Route::post('/subscriptions/plans',                    [AdminSubscriptionPlanController::class, 'store'])
        ->name('subscriptions.plans.store');
    Route::get('/subscriptions/plans/{plan}/edit',        [AdminSubscriptionPlanController::class, 'edit'])
        ->name('subscriptions.plans.edit');
    Route::put('/subscriptions/plans/{plan}',             [AdminSubscriptionPlanController::class, 'update'])
        ->name('subscriptions.plans.update');
    Route::delete('/subscriptions/plans/{plan}',          [AdminSubscriptionPlanController::class, 'destroy'])
        ->name('subscriptions.plans.destroy');
 
});