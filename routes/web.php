<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GymController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\CityManagerController;
use App\Http\Controllers\GymManagerController;
use App\Http\Controllers\AllUsersController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\EmptyController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\TrainingPackagesController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CourseController as WebCourseController;
use App\Http\Controllers\BeltController;
use App\Http\Controllers\ParticipationController;
use App\Http\Controllers\CourseAttendanceController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserPaymentController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\AthleteTestController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseSubcategoryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryAttendanceController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ModeratorController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\BeltNotesController;
use App\Http\Controllers\CashRegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['fr', 'ar', 'en'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

#=======================================================================================#
#                                   Settings Routes                                     #
#=======================================================================================#
Route::middleware(['auth', 'logs-out-banned-user', 'isUser', 'role:admin'])->group(function () {
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('/settings/custom-folders', [SettingController::class, 'customFolders'])->name('settings.custom-folders.index');
    Route::post('/settings/custom-folders', [SettingController::class, 'storeCustomFolders'])->name('settings.custom-folders.store');
    Route::get('/settings/custom-folders/files/{file}', [SettingController::class, 'downloadCustomFolderFile'])->name('settings.custom-folders.files.download');
});

#=======================================================================================#
#			                           Home Route                               	    #
#=======================================================================================#
Route::middleware(['auth', 'logs-out-banned-user', 'isUser'])->group(function () {
    Route::get('/PaymentPackage/stripe', [StripeController::class, 'stripe'])->name('PaymentPackage.stripe')->middleware('role:admin|cityManager|gymManager');
    Route::post('/PaymentPackage/stripe', [StripeController::class, 'stripePost'])->name('stripe.post')->middleware('role:admin|cityManager|gymManager');
    Route::get('/PaymentPackage/purchase_history', [StripeController::class, 'index'])->name('PaymentPackage.purchase_history')->middleware('role:admin|cityManager|gymManager');
    Route::get('/', [WelcomeController::class, 'index'])->name('welcome')->middleware('role:admin|cityManager|gymManager|coach');
});

Route::get('/global-search', function (\Illuminate\Http\Request $request) {
    $q = trim((string) $request->query('q', ''));
    if (mb_strlen($q) < 2) {
        return response()->json(['results' => []]);
    }

    $normalized = mb_strtolower($q);
    $escaped = addcslashes($q, '%_');

    $authUser = auth()->user();
    $userRole = $authUser?->getRoleNames()?->first();

    $gymIdsForCity = null;
    if ($authUser && $authUser->hasRole('cityManager')) {
        $gymIdsForCity = \App\Models\Gym::query()
            ->where('city_id', $authUser->city_id)
            ->pluck('id')
            ->all();
    }

    $results = [];

    // --- Suggestion de Pages ---
    $results[] = [
        'type' => 'Page',
        'title' => "Membres: {$q}",
        'subtitle' => 'Rechercher un membre par nom/email',
        'url' => route('allUsers.list', ['q' => $q]),
    ];

    if (
        str_contains($normalized, 'paiement') ||
        str_contains($normalized, 'paiements') ||
        str_contains($normalized, 'payment') ||
        str_contains($normalized, 'payments') ||
        str_contains($normalized, 'invoice') ||
        str_contains($normalized, 'facture')
    ) {
        $results[] = [
            'type' => 'Page',
            'title' => 'Paiements (par membre)',
            'subtitle' => 'Ouvrir un membre puis consulter ses paiements',
            'url' => route('allUsers.list'),
        ];

        if ($authUser && $authUser->hasAnyRole(['admin', 'cityManager', 'gymManager'])) {
            $results[] = [
                'type' => 'Page',
                'title' => 'Historique des achats (Stripe)',
                'subtitle' => null,
                'url' => route('PaymentPackage.purchase_history'),
            ];
        }
    }

    if (str_contains($normalized, 'presence') || str_contains($normalized, 'présence') || str_contains($normalized, 'attendance')) {
        $results[] = [
            'type' => 'Page',
            'title' => 'Présences',
            'subtitle' => null,
            'url' => route('attendance.create'),
        ];
    }

    if ((str_contains($normalized, 'event') || str_contains($normalized, 'événement') || str_contains($normalized, 'evenement')) && \Illuminate\Support\Facades\Route::has('events.index')) {
        $results[] = [
            'type' => 'Page',
            'title' => 'Événements',
            'subtitle' => null,
            'url' => route('events.index'),
        ];
    }

    if ((str_contains($normalized, 'report') || str_contains($normalized, 'rapport')) && \Illuminate\Support\Facades\Route::has('reports.index')) {
        $results[] = [
            'type' => 'Page',
            'title' => 'Rapports',
            'subtitle' => null,
            'url' => route('reports.index'),
        ];
    }

    // --- Recherche par Modèles ---

    // 1. Utilisateurs (Membres, Staff, etc.)
    $usersQuery = \App\Models\User::query()->select(['id', 'name', 'phone', 'email', 'gym_id', 'city_id']);
    if ($authUser && $authUser->hasRole('gymManager')) {
        $usersQuery->where('gym_id', $authUser->gym_id);
    } elseif ($authUser && $authUser->hasRole('cityManager')) {
        $usersQuery->where('city_id', $authUser->city_id);
    } elseif ($authUser && $authUser->hasRole('coach')) {
        $usersQuery->where('gym_id', $authUser->gym_id);
    }
    $users = $usersQuery
        ->where(function ($query) use ($escaped) {
            $query
                ->where('name', 'like', "%{$escaped}%")
                ->orWhere('phone', 'like', "%{$escaped}%")
                ->orWhere('email', 'like', "%{$escaped}%");
        })
        ->with('roles')
        ->limit(10)
        ->get();

    foreach ($users as $user) {
        $roleName = $user->roles->first()?->name;
        $type = 'Membre';
        if ($roleName === 'coach') $type = 'Coach';
        elseif ($roleName === 'gymManager') $type = 'Manager de salle';
        elseif ($roleName === 'cityManager') $type = 'Manager de ville';
        elseif ($roleName === 'admin') $type = 'Admin';
        elseif ($roleName === 'user') $type = 'Athlète';

        $results[] = [
            'type' => $type,
            'title' => $user->name,
            'subtitle' => $user->phone ?: ($user->email ?: null),
            'url' => route('allUsers.show', ['id' => $user->id]),
        ];
    }

    // 2. Salles (Gyms)
    if ($authUser && $authUser->hasAnyRole(['admin', 'cityManager'])) {
        $gymsQuery = \App\Models\Gym::query()->select(['id', 'name', 'city_id']);
        if ($authUser->hasRole('cityManager')) {
            $gymsQuery->where('city_id', $authUser->city_id);
        }
        $gyms = $gymsQuery->where('name', 'like', "%{$escaped}%")->limit(5)->get();
        foreach ($gyms as $gym) {
            $results[] = [
                'type' => 'Salle',
                'title' => $gym->name,
                'subtitle' => $gym->city?->name,
                'url' => route('gym.show', ['id' => $gym->id]),
            ];
        }
    }

    // 3. Villes (Cities)
    if ($authUser && $authUser->hasRole('admin')) {
        $cities = \App\Models\City::query()
            ->where('name', 'like', "%{$escaped}%")
            ->limit(3)
            ->get();
        foreach ($cities as $city) {
            $results[] = [
                'type' => 'Ville',
                'title' => $city->name,
                'subtitle' => 'Gestion des villes',
                'url' => route('city.list'), // Pas de show spécifique souvent, on renvoie vers la liste
            ];
        }
    }

    // 4. Cours (Courses)
    $coursesQuery = \App\Models\Course::query()->select(['id', 'name', 'description', 'gym_id']);
    if ($authUser && $authUser->hasRole('gymManager')) {
        $coursesQuery->where('gym_id', $authUser->gym_id);
    } elseif ($authUser && $authUser->hasRole('cityManager')) {
        $coursesQuery->whereIn('gym_id', $gymIdsForCity ?? []);
    } elseif ($authUser && $authUser->hasRole('coach')) {
        $coursesQuery->where('gym_id', $authUser->gym_id);
    }
    $courses = $coursesQuery
        ->where(function ($query) use ($escaped) {
            $query
                ->where('name', 'like', "%{$escaped}%")
                ->orWhere('description', 'like', "%{$escaped}%");
        })
        ->limit(6)
        ->get();

    foreach ($courses as $course) {
        $results[] = [
            'type' => 'Cours',
            'title' => $course->name,
            'subtitle' => $course->description ?: null,
            'url' => route('course.show', ['course' => $course->id]),
        ];
    }

    // 5. Forfaits (Training Packages)
    if ($authUser && $authUser->hasAnyRole(['admin', 'cityManager', 'gymManager'])) {
        $packages = \App\Models\TrainingPackage::query()
            ->where('name', 'like', "%{$escaped}%")
            ->limit(5)
            ->get();
        foreach ($packages as $package) {
            $results[] = [
                'type' => 'Forfait',
                'title' => $package->name,
                'subtitle' => "{$package->price} DH - {$package->sessions_number} séances",
                'url' => route('trainingPackeges.show_training_package', ['session' => $package->id]),
            ];
        }
    }

    // 6. Séances (Training Sessions)
    $sessionsQuery = \App\Models\TrainingSession::query()->select(['id', 'name', 'day', 'starts_at']);
    // Filtrage par salle si nécessaire (TrainingSession n'a pas direct gym_id mais via package)
    $sessions = $sessionsQuery->where('name', 'like', "%{$escaped}%")->limit(5)->get();
    foreach ($sessions as $session) {
        $results[] = [
            'type' => 'Séance',
            'title' => $session->name,
            'subtitle' => "{$session->day} à {$session->starts_at}",
            'url' => route('TrainingSessions.show_training_session', ['session' => $session->id]),
        ];
    }

    // 7. Événements (Events)
    $events = \App\Models\Event::query()
        ->select(['id', 'name', 'location', 'start_at'])
        ->where(function ($query) use ($escaped) {
            $query
                ->where('name', 'like', "%{$escaped}%")
                ->orWhere('location', 'like', "%{$escaped}%");
        })
        ->orderByDesc('start_at')
        ->limit(6)
        ->get();

    foreach ($events as $event) {
        $results[] = [
            'type' => 'Événement',
            'title' => $event->name,
            'subtitle' => $event->location ?: null,
            'url' => route('events.show', ['id' => $event->id]),
        ];
    }

    // 8. Dépenses (Expenses)
    if ($authUser && $authUser->hasAnyRole(['admin', 'cityManager', 'gymManager'])) {
        $expensesQuery = \App\Models\Expense::query();
        if ($authUser->hasRole('gymManager')) {
            $expensesQuery->where('gym_id', $authUser->gym_id);
        }
        $expenses = $expensesQuery->where('notes', 'like', "%{$escaped}%")
            ->with(['category', 'gym'])
            ->limit(5)
            ->get();
        foreach ($expenses as $expense) {
            $results[] = [
                'type' => 'Dépense',
                'title' => "Dépense: " . ($expense->category?->name ?? 'Général'),
                'subtitle' => "{$expense->amount_cents} DH - {$expense->notes}",
                'url' => route('expenses.index'), // Souvent pas de show unique, on renvoie vers la liste
            ];
        }
    }

    // 9. Tests Athlètes
    if ($authUser && $authUser->hasAnyRole(['admin', 'cityManager', 'gymManager', 'coach'])) {
        $tests = \App\Models\AthleteTest::query()
            ->where('name_fr', 'like', "%{$escaped}%")
            ->orWhere('name_ar', 'like', "%{$escaped}%")
            ->limit(5)
            ->get();
        foreach ($tests as $test) {
            $results[] = [
                'type' => 'Test',
                'title' => $test->name_fr ?: $test->name_ar,
                'subtitle' => 'Test de performance athlète',
                'url' => route('athlete-tests.index'),
            ];
        }
    }

    // Limiter les résultats totaux pour ne pas surcharger l'interface
    $results = array_slice($results, 0, 15);

    return response()->json([
        'results' => $results,
        'context' => [
            'role' => $userRole,
        ],
    ]);
})
    ->name('global-search')
    ->middleware('auth')
    ->middleware('logs-out-banned-user')
    ->middleware('isUser')
    ->middleware('role:admin|cityManager|gymManager|coach');
#=======================================================================================#
#                                   Event Routes                                       #
#=======================================================================================#
Route::controller(EventController::class)->group(function () {
    Route::get('/events/{id}/live', 'liveResults')->name('events.live');

    Route::middleware(['auth', 'logs-out-banned-user'])->group(function () {
        Route::get('/events', 'index')->name('events.index');
        
        // Routes réservées au staff (placées AVANT {id} pour éviter les conflits)
        Route::middleware(['isUser'])->group(function () {
            Route::get('/events/create', 'create')->name('events.create')->middleware('role:admin|cityManager|gymManager|coach');
            Route::post('/events', 'store')->name('events.store')->middleware('role:admin|cityManager|gymManager|coach');
        });

        Route::get('/events/{id}', 'show')->name('events.show');
        
        // Routes spécifiques aux utilisateurs
        Route::get('/events/{id}/register', 'register')->name('events.register')->middleware('role:user|admin|cityManager|gymManager|coach');
        Route::post('/events/{id}/register', 'storeRegistration')->name('events.storeRegistration')->middleware('role:user|admin|cityManager|gymManager|coach');

        // Autres routes staff
        Route::middleware(['isUser'])->group(function () {
            Route::get('/events/{id}/export-pdf', 'exportPdf')->name('events.export-pdf')->middleware('role:admin|cityManager|gymManager|coach');
            Route::post('/events/{id}/registrations/{registration}/mark-paid', 'markRegistrationPaid')->name('events.registrations.markPaid')->middleware('role:admin|cityManager|gymManager');
        });
    });
});
#=======================================================================================#
#			                        Gym Controller Routes                              	#
#=======================================================================================#
Route::controller(GymController::class)->middleware(['auth', 'logs-out-banned-user', 'isUser'])->group(function () {
    Route::get('/gym/create', 'create')->name('gym.create')->middleware('role:admin|cityManager');
    Route::post('/gym/store', 'store')->name('gym.store')->middleware('role:admin|cityManager');
    Route::get('/gym/edit/{gym}', 'edit')->name('gym.edit')->middleware('role:admin|cityManager');
    Route::put('/gym/update/{gym}', 'update')->name('gym.update')->middleware('role:admin|cityManager');
    Route::delete('/gym/{id}', 'deleteGym')->name('gym.delete')->middleware('role:admin|cityManager');
    Route::get('/gym/list', 'list')->name('gym.list')->middleware('role:admin|cityManager');
    Route::get('/gym/show/{id}', 'show')->name('gym.show')->middleware('role:admin|cityManager');
});

Route::get('/gym/training', function () {
    return view('gym.training_session')->name('gym.session');
})->middleware('auth')->middleware('logs-out-banned-user')->middleware('isUser')->middleware('role:admin|cityManager|gymManager');

#=======================================================================================#
#			                    Coach Controller Routes                              	#
#=======================================================================================#
Route::controller(CoachController::class)->middleware(['auth', 'logs-out-banned-user', 'isUser'])->group(function () {
    Route::get('/coach/create', 'create')->name('coach.create')->middleware('role:admin|cityManager|gymManager');
    Route::post('/coach/store', 'store')->name('coach.store')->middleware('role:admin|cityManager|gymManager');
    Route::get('/coach/edit/{coach}', 'edit')->name('coach.edit')->middleware('role:admin|cityManager|gymManager');
    Route::put('/coach/update/{coach}', 'update')->name('coach.update')->middleware('role:admin|cityManager|gymManager');
    Route::delete('/coach/{id}', 'deleteCoach')->name('coach.delete')->middleware('role:admin|cityManager|gymManager');
    Route::get('/coach/list', 'list')->name('coach.list')->middleware('role:admin|cityManager|gymManager');
    Route::get('/coach/show/{coach}', 'show')->name('coach.show')->middleware('role:admin|cityManager|gymManager|coach');
    Route::get('/coach/{coach}/report', 'report')->name('coach.report')->middleware('role:admin|cityManager|gymManager');
});

Route::post('/coach/{coach}/attendance', [AttendanceController::class, 'storeForCoach'])->name('coach.attendance.store')->middleware('auth')->middleware('logs-out-banned-user')->middleware('isUser')->middleware('role:admin|cityManager|gymManager|coach');

#=======================================================================================#
#			                          Admin Routes                                  	#
#=======================================================================================#
Route::group(['middleware' => ['auth', 'logs-out-banned-user', 'isUser']], function () {
    Route::get('/user/show-profile', [UserController::class, 'show_profile'])->name('user.admin_profile_self')->middleware('logs-out-banned-user')->middleware('role:admin');
    Route::get('/user/edit-profile', [UserController::class, 'edit_profile'])->name('user.edit_admin_profile_self')->middleware('logs-out-banned-user')->middleware('role:admin');
    Route::get('/gym/training_session', [TrainingController::class, 'create'])->name('gym.training_session')->middleware('logs-out-banned-user')->middleware('role:admin');
    Route::get('/attendance/mark', [AttendanceController::class, 'create'])->name('attendance.create')->middleware('role:admin|cityManager|gymManager|coach');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store')->middleware('role:admin|cityManager|gymManager|coach');
    Route::post('/attendance/ajax-update', [AttendanceController::class, 'ajaxUpdate'])->name('attendance.ajaxUpdate')->middleware('role:admin|cityManager|gymManager|coach');

    // Logs d'activité
    Route::get('/activity-logs', [App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-logs.index')->middleware('role:admin');
    Route::get('/activity-logs/{id}', [App\Http\Controllers\ActivityLogController::class, 'show'])->name('activity-logs.show')->middleware('role:admin');

    // Caisses
    Route::get('cash-registers/by-gym/{gymId}', [CashRegisterController::class, 'getByGym'])->name('cash-registers.by-gym');
    Route::resource('cash-registers', CashRegisterController::class)->middleware('role:admin|cityManager|gymManager');
});


#=======================================================================================#
#			                         Training Routes                                  	#
#=======================================================================================#
Route::middleware(['auth', 'logs-out-banned-user', 'isUser'])->group(function () {
    Route::get('/TrainingSessions/index', [TrainingController::class, 'index'])->name('TrainingSessions.listSessions')->middleware('role:admin|cityManager|gymManager|coach');
    Route::get('/TrainingSessions/create_session', [TrainingController::class, 'create'])->name('TrainingSessions.training_session')->middleware('role:admin|cityManager|gymManager|coach');
    Route::post('/TrainingSessions/sessions', [TrainingController::class, 'store'])->name('TrainingSessions.store')->middleware('role:admin|cityManager|gymManager|coach');
    Route::post('/TrainingSessions/plan', [TrainingController::class, 'planStore'])->name('TrainingSessions.plan')->middleware('role:admin|cityManager|gymManager|coach');
    Route::get('/TrainingSessions/sessions/{session}', [TrainingController::class, 'show'])->name('TrainingSessions.show_training_session')->middleware('role:admin|cityManager|gymManager|coach');
    Route::get('/TrainingSessions/{session}/edit', [TrainingController::class, 'edit'])->name('TrainingSessions.edit_training_session')->middleware('role:admin|cityManager|gymManager');
    Route::delete('/TrainingSessions/{session}  ', [TrainingController::class, 'deleteSession'])->name('TrainingSessions.delete_session')->middleware('role:admin|cityManager|gymManager');
    Route::put('/TrainingSessions/{session}', [TrainingController::class, 'update'])->name('TrainingSessions.update_session')->middleware('role:admin|cityManager|gymManager');
});


#=======================================================================================#
#			                            Packages Routes                                 	#
#=======================================================================================#
Route::middleware(['auth', 'logs-out-banned-user', 'isUser'])->group(function () {
    Route::get('/trainingPackeges/index', [TrainingPackagesController::class, 'index'])->name('trainingPackeges.listPackeges')->middleware('role:admin|cityManager|gymManager|coach');
    Route::get('/trainingPackeges/create_package', [TrainingPackagesController::class, 'create'])->name('trainingPackeges.creatPackege')->middleware('role:admin|cityManager|gymManager');
    Route::post('/trainingPackeges/package', [TrainingPackagesController::class, 'store'])->name('trainingPackeges.store')->middleware('role:admin|cityManager|gymManager');
    Route::get('/trainingPackeges/package/{session}', [TrainingPackagesController::class, 'show'])->name('trainingPackeges.show_training_package')->middleware('role:admin|cityManager|gymManager|coach');
    Route::get('/trainingPackeges/{package}/edit', [TrainingPackagesController::class, 'edit'])->name('trainingPackeges.editPackege')->middleware('role:admin');
    Route::delete('/trainingPackeges/{package}  ', [TrainingPackagesController::class, 'deletePackage'])->name('trainingPackeges.delete_package')->middleware('role:admin');
    Route::put('/trainingPackeges/{package}', [TrainingPackagesController::class, 'update'])->name('trainingPackeges.update_package')->middleware('role:admin');
});
#=======================================================================================#
#			                            User Routes                                   	#
#=======================================================================================#
Route::middleware(['auth', 'logs-out-banned-user', 'isUser'])->group(function () {
    Route::get('/user/{id}', [UserController::class, 'show_profile'])->name('user.admin_profile')->middleware('role:admin|cityManager|gymManager');
    Route::get('/user/{users}/edit-profile', [UserController::class, 'edit_profile'])->name('user.edit_admin_profile')->middleware('role:admin|cityManager|gymManager');
    Route::put('/user/{users}', [UserController::class, 'update'])->name('user.update')->middleware('role:admin|cityManager|gymManager');
    Route::get('/user', [UserController::class, 'index'])->name('layouts.user-layout')->middleware('role:admin|cityManager|gymManager');
});
#=======================================================================================#
#			                            Auth Routes                                  	#
#=======================================================================================#
// Routes d'authentification avec protection contre les attaques par force brute
Route::middleware(['rate-limit-login'])->group(function () {
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.post');
    // Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
});

// Autres routes d'authentification sans rate limiting
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
// Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
Route::get('/password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
#=======================================================================================#
#			                           City Managers Routes                          	#
#=======================================================================================#
Route::controller(CityManagerController::class)->middleware(['auth', 'logs-out-banned-user', 'isUser'])->group(function () {
    Route::get('/cityManager/create', 'create')->name('cityManager.create')->middleware('role:admin');
    Route::post('/cityManager/store', 'store')->name('cityManager.store')->middleware('role:admin');
    Route::get('/cityManager/list', 'list')->name('cityManager.list')->middleware('role:admin');
    Route::get('/cityManager/edit/{id}', 'edit')->name('cityManager.edit')->middleware('role:admin');
    Route::put('/cityManager/update/{id}', 'update')->name('cityManager.update')->middleware('role:admin');
    Route::delete('/cityManager/{id}', 'deletecityManager')->name('cityManager.delete')->middleware('role:admin');
    Route::get('/cityManager/show/{id}', 'show')->name('cityManager.show')->middleware('role:admin');
});
#=======================================================================================#
#			                           Gym Managers Routes                          	#
#=======================================================================================#
Route::controller(GymManagerController::class)->middleware(['auth', 'logs-out-banned-user', 'isUser'])->group(function () {
    Route::get('/gymManager/create', 'create')->name('gymManager.create')->middleware('role:admin|cityManager');
    Route::post('/gymManager/store', 'store')->name('gymManager.store')->middleware('role:admin|cityManager');
    Route::get('/gymManager/list', 'list')->name('gymManager.list')->middleware('role:admin|cityManager|gymManager');
    Route::get('/gymManager/edit/{gym}', 'edit')->name('gymManager.edit')->middleware('role:admin|cityManager|gymManager');
    Route::put('/gymManager/update/{gym}', 'update')->name('gymManager.update')->middleware('role:admin|cityManager|gymManager');
    Route::delete('/gymManager/{id}', 'deletegymManager')->name('gymManager.delete')->middleware('role:admin|cityManager|gymManager');
    Route::get('/gymManager/show/{id}', 'show')->name('gymManager.show')->middleware('role:admin|cityManager|gymManager');
});
#=======================================================================================#
#			                            Ban User                              	        #
#=======================================================================================#
Route::get('/banUser/{userID}', [UserController::class, 'banUser'])->name('user.banUser')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
Route::get('/listBanned', [UserController::class, 'listBanned'])->name('user.listBanned')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
Route::PATCH('/unBan/{userID}', [UserController::class, 'unBan'])->name('user.unBan')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
// Route::get('/unBan/{userID}', [UserController::class, 'unBan'])->name('user.unBan')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
#=======================================================================================#
#			                           Training Packages                              	#
#=======================================================================================#
// Route::get('/trainingPackeges/list', [UserController::class, ''])->name('')->middleware('auth')->middleware('');


#=======================================================================================#
#			                            All users Route                          	    #
#=======================================================================================#
Route::controller(AllUsersController::class)->group(function () {
    Route::get('/allUsers/list', 'list')->name('allUsers.list')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::get('/allUsers/stages', 'stages')->name('allUsers.stages')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::post('/allUsers/stages/update', 'updateStage')->name('allUsers.stages.update')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::get('/allUsers/show/{id}', 'show')->name('allUsers.show')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::post('/allUsers/{id}/group', 'assignGroup')->name('allUsers.group.assign')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::delete('/allUsers/{id}', 'deleteUser')->name('allUsers.delete')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::get('/allUsers/addGym/{id}', 'addGym')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::post('/allUsers/submitGym/{id}', 'submitGym')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::get('/allUsers/create', 'create')->name('allUsers.create')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::post('/allUsers', 'store')->name('allUsers.store')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::get('/allUsers/{id}/edit', 'edit')->name('allUsers.edit')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::put('/allUsers/{id}', 'update')->name('allUsers.update')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::get('/allUsers/{id}/report', 'report')->name('allUsers.report')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
});

#=======================================================================================#
#                               User Payments Routes                                   #
#=======================================================================================#
Route::controller(UserPaymentController::class)->group(function () {
    Route::get('/users/{user}/payments', 'index')->name('user-payments.index')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::get('/users/{user}/payments/create', 'create')->name('user-payments.create')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::post('/users/{user}/payments', 'store')->name('user-payments.store')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::get('/payments/{payment}/edit', 'edit')->name('user-payments.edit')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::put('/payments/{payment}', 'update')->name('user-payments.update')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::delete('/payments/{payment}', 'destroy')->name('user-payments.destroy')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::get('/payments/{payment}/invoice', 'downloadInvoice')->name('user-payments.invoice')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
});

#=======================================================================================#
#                                   Expenses Routes                                     #
#=======================================================================================#
Route::controller(ExpenseController::class)->group(function () {
    Route::get('/expenses', 'index')->name('expenses.index')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::get('/expenses/create', 'create')->name('expenses.create')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::post('/expenses', 'store')->name('expenses.store')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::get('/expenses/{expense}', 'show')->whereNumber('expense')->name('expenses.show')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::get('/expenses/{expense}/edit', 'edit')->name('expenses.edit')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::put('/expenses/{expense}', 'update')->name('expenses.update')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::delete('/expenses/{expense}', 'destroy')->name('expenses.destroy')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::get('/expenses/coach/{coach}/report', 'coachReport')->name('expenses.coach_report')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
});

Route::controller(ExpenseCategoryController::class)->group(function () {
    Route::get('/expense-categories', 'index')->name('expense-categories.index')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::get('/expense-categories/create', 'create')->name('expense-categories.create')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::post('/expense-categories', 'store')->name('expense-categories.store')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::get('/expense-categories/{category}/edit', 'edit')->name('expense-categories.edit')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::put('/expense-categories/{category}', 'update')->name('expense-categories.update')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::delete('/expense-categories/{category}', 'destroy')->name('expense-categories.destroy')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
});

Route::controller(ExpenseSubcategoryController::class)->group(function () {
    Route::get('/expense-subcategories', 'index')->name('expense-subcategories.index')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::get('/expense-subcategories/create', 'create')->name('expense-subcategories.create')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::post('/expense-subcategories', 'store')->name('expense-subcategories.store')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::get('/expense-subcategories/{subcategory}/edit', 'edit')->name('expense-subcategories.edit')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::put('/expense-subcategories/{subcategory}', 'update')->name('expense-subcategories.update')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::delete('/expense-subcategories/{subcategory}', 'destroy')->name('expense-subcategories.destroy')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
});

#=======================================================================================#
#                                   Categories Routes                                   #
#=======================================================================================#
Route::controller(CategoryController::class)->group(function () {
    Route::get('/categories', 'list')->name('category.list')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::get('/categories/create', 'create')->name('category.create')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::post('/categories', 'store')->name('category.store')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::post('/categories/seed-defaults', 'seedDefaults')->name('category.seed-defaults')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::get('/categories/{category}/edit', 'edit')->name('category.edit')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::put('/categories/{category}', 'update')->name('category.update')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::delete('/categories/{category}', 'destroy')->name('category.destroy')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
});

#=======================================================================================#
#                                   Belt Routes                                        #
#=======================================================================================#
Route::controller(BeltController::class)->group(function () {
    Route::get('/belts/list', 'list')->name('belt.list')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::get('/belts/create', 'create')->name('belt.create')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::post('/belts/store', 'store')->name('belt.store')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::get('/belts/{belt}', 'show')->name('belt.show')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::get('/belts/{belt}/edit', 'edit')->name('belt.edit')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::put('/belts/{belt}', 'update')->name('belt.update')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::delete('/belts/{belt}', 'destroy')->name('belt.destroy')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::get('/belts/grading/create', 'grading')->name('belt.grading')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::post('/belts/grading', 'storeGrading')->name('belt.storeGrading')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
});

Route::controller(BeltNotesController::class)->group(function () {
    Route::get('/belt-notes', 'index')->name('belt.notes.index')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::post('/belt-notes/categories', 'storeCategory')->name('belt.notes.categories.store')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::delete('/belt-notes/categories/{category}', 'destroyCategory')->name('belt.notes.categories.destroy')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::post('/belt-notes/categories/{category}/subcategories', 'storeSubcategory')->name('belt.notes.subcategories.store')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::delete('/belt-notes/subcategories/{subcategory}', 'destroySubcategory')->name('belt.notes.subcategories.destroy')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::post('/belt-notes/categories/{category}/notes', 'storeCategoryNote')->name('belt.notes.categories.notes.store')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::post('/belt-notes/subcategories/{subcategory}/notes', 'storeSubcategoryNote')->name('belt.notes.subcategories.notes.store')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::delete('/belt-notes/notes/{note}', 'destroyNote')->name('belt.notes.notes.destroy')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
});


#=======================================================================================#
#			                            City route                                      #
#=======================================================================================#

//GET, 	    /photos, 	    index,  	city.index
Route::get('/cities', [CityController::class, 'list'])->name('city.list')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin');

//GET, 	/city/create, 	create, 	city.create
Route::get('/cities/create', [CityController::class, 'create'])->name('city.create')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin');
//POST, 	/city, 	store,       	city.store
Route::post('/cities', [CityController::class, 'store'])->name('city.store')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin');

//GET, 	/city/{photo}, 	show,    	city.show
Route::get('/cities/{cityID}', [CityController::class, 'show'])->name('city.show')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin');

//GET 	/city/{cityID}/edit 	edit 	city.edit
Route::get('/cities/{cityID}/edit', [CityController::class, 'edit'])->name('city.edit')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin');
//PUT/PATCH, 	/city/{cityID}, 	update, 	city.update
Route::put('/cities/{cityID}', [CityController::class, 'update'])->name('city.update')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin');

//DELETE 	/city/{cityID} 	destroy 	city.destroy
Route::delete('/cities/{cityID}', [CityController::class, 'destroy'])->name('city.destroy')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin');
Route::get('/restoredCities', [CityController::class, 'showDeleted'])->name('city.showDeleted')->middleware('auth')->middleware('role:admin');
Route::get('/restoredCities/{postID}', [CityController::class, 'restore'])->name('city.restored')->middleware('auth')->middleware('role:admin');
#=======================================================================================#
#			                           Attendance route                                  #
#=======================================================================================#
Route::get('/listHistory', [AttendanceController::class, 'listHistory'])->name('attendance')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
#=======================================================================================#
#                                   Courses route                                      #
#=======================================================================================#
Route::get('/cours', [WebCourseController::class, 'list'])->name('course.list')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
Route::get('/cours/create', [WebCourseController::class, 'create'])->name('course.create')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
Route::post('/cours', [WebCourseController::class, 'store'])->name('course.store')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
Route::get('/cours/{course}', [WebCourseController::class, 'show'])->name('course.show')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
Route::get('/cours/{course}/edit', [WebCourseController::class, 'edit'])->name('course.edit')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
Route::put('/cours/{course}', [WebCourseController::class, 'update'])->name('course.update')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
Route::delete('/cours/{course}', [WebCourseController::class, 'destroy'])->name('course.delete')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');

Route::get('/courses', [WebCourseController::class, 'list'])->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
Route::get('/courses/create', [WebCourseController::class, 'create'])->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
Route::post('/courses', [WebCourseController::class, 'store'])->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
Route::get('/courses/{course}', [WebCourseController::class, 'show'])->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
Route::get('/courses/{course}/edit', [WebCourseController::class, 'edit'])->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
Route::put('/courses/{course}', [WebCourseController::class, 'update'])->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
Route::delete('/courses/{course}', [WebCourseController::class, 'destroy'])->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
Route::controller(\App\Http\Controllers\GroupController::class)->group(function(){
    Route::get('/groups', 'index')->name('group.index')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::get('/groups/create', 'create')->name('group.create')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::post('/groups', 'store')->name('group.store')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::get('/groups/{group}', 'show')->name('group.show')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::get('/groups/{group}/edit', 'edit')->name('group.edit')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::put('/groups/{group}', 'update')->name('group.update')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::delete('/groups/{group}', 'destroy')->name('group.destroy')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::post('/groups/{group}/add-member', 'addMember')->name('group.addMember')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::delete('/groups/{group}/remove-member/{userId}', 'removeMember')->name('group.removeMember')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::post('/groups/assign-all-to-A', 'assignAllToGroupA')->name('group.assignAllToA')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
});
#=======================================================================================#
#                                   Participation route                                #
#=======================================================================================#
Route::get('/participations', [ParticipationController::class, 'index'])->name('participation.list')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
Route::post('/participations/waitlist', [ParticipationController::class, 'addToWaitlist'])->name('participation.waitlist')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
Route::get('/participations/create', [ParticipationController::class, 'create'])->name('participation.create')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
Route::post('/participations', [ParticipationController::class, 'store'])->name('participation.store')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
Route::get('/participations/{participation}', [ParticipationController::class, 'show'])->name('participation.show')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
Route::get('/participations/{participation}/edit', [ParticipationController::class, 'edit'])->name('participation.edit')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
Route::put('/participations/{participation}', [ParticipationController::class, 'update'])->name('participation.update')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
Route::delete('/participations/{participation}', [ParticipationController::class, 'destroy'])->name('participation.destroy')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
#=======================================================================================#
#                                Course Attendance Routes                              #
#=======================================================================================#
    # Course Attendance Management Routes
    #=======================================================================================#
    Route::get('/course-attendance', [CourseAttendanceController::class, 'index'])->name('course-attendance.index')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::get('/course-attendance/course/{course}', [CourseAttendanceController::class, 'showCourse'])->name('course-attendance.course')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::get('/course-attendance/{course}/create', [CourseAttendanceController::class, 'create'])->name('course-attendance.create')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::post('/course-attendance/{course}/store', [CourseAttendanceController::class, 'store'])->name('course-attendance.store')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::post('/course-attendance/ajax-update', [CourseAttendanceController::class, 'ajaxUpdate'])->name('course-attendance.ajaxUpdate')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::get('/course-attendance/{course}/statistics', [CourseAttendanceController::class, 'statistics'])->name('course-attendance.statistics')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::get('/course-attendance/{course}/{date}', [CourseAttendanceController::class, 'show'])->where('date', '\\d{4}-\\d{2}-\\d{2}')->name('course-attendance.show')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::get('/course-attendance/{course}/{date}/edit', [CourseAttendanceController::class, 'edit'])->where('date', '\\d{4}-\\d{2}-\\d{2}')->name('course-attendance.edit')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::put('/course-attendance/{course}/{date}', [CourseAttendanceController::class, 'update'])->where('date', '\\d{4}-\\d{2}-\\d{2}')->name('course-attendance.update')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::delete('/course-attendance/{course}/{date}', [CourseAttendanceController::class, 'destroy'])->where('date', '\\d{4}-\\d{2}-\\d{2}')->name('course-attendance.destroy')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
#=======================================================================================#
#                                      Combats                                          #
#=======================================================================================#
    Route::controller(\App\Http\Controllers\CombatController::class)->group(function () {
        Route::get('/combats', 'index')->name('combats.index')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
        Route::get('/combats/create', 'create')->name('combats.create')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
        Route::post('/combats', 'store')->name('combats.store')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    });
#=======================================================================================#
#                             Category Attendance Routes                               #
#=======================================================================================#
    Route::get('/category-attendance', [CategoryAttendanceController::class, 'index'])->name('category-attendance.index')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::get('/category-attendance/{category}/create', [CategoryAttendanceController::class, 'create'])->name('category-attendance.create')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::post('/category-attendance/{category}/store', [CategoryAttendanceController::class, 'store'])->name('category-attendance.store')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::post('/category-attendance/ajax-update', [CategoryAttendanceController::class, 'ajaxUpdate'])->name('category-attendance.ajaxUpdate')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::get('/category-attendance/{category}/statistics', [CategoryAttendanceController::class, 'statistics'])->name('category-attendance.statistics')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::get('/category-attendance/{category}/{date}', [CategoryAttendanceController::class, 'show'])->where('date', '\\d{4}-\\d{2}-\\d{2}')->name('category-attendance.show')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::get('/category-attendance/{category}/{date}/edit', [CategoryAttendanceController::class, 'edit'])->where('date', '\\d{4}-\\d{2}-\\d{2}')->name('category-attendance.edit')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::put('/category-attendance/{category}/{date}', [CategoryAttendanceController::class, 'update'])->where('date', '\\d{4}-\\d{2}-\\d{2}')->name('category-attendance.update')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::delete('/category-attendance/{category}/{date}', [CategoryAttendanceController::class, 'destroy'])->where('date', '\\d{4}-\\d{2}-\\d{2}')->name('category-attendance.destroy')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
#=======================================================================================#
#			                            empty statement                                 #
#=======================================================================================#
Route::get('/empty', [EmptyController::class, 'empty'])->name('empty.statement')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
#=======================================================================================#
#			                           not Found route                                  #
#=======================================================================================#
Route::get('/unAuth', [EmptyController::class, 'unAuth'])->name('500')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
#=======================================================================================#
#                                   Reports Routes                                     #
#=======================================================================================#
Route::controller(ReportController::class)->group(function () {
    Route::get('/reports', 'index')->name('reports.index')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::get('/reports/export/pdf', 'exportPdf')->name('reports.export.pdf')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::get('/reports/export/excel', 'exportExcel')->name('reports.export.excel')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
});
Route::controller(AthleteTestController::class)->group(function(){
    Route::get('/athlete-tests', 'index')->name('athlete-tests.index')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::post('/athlete-tests/seed-defaults', 'seedDefaults')->name('athlete-tests.seed')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::post('/athlete-tests/seed-replace', 'seedReplace')->name('athlete-tests.seed.replace')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::get('/athlete-tests/create', 'createTest')->name('athlete-tests.manage.create')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::post('/athlete-tests', 'storeTest')->name('athlete-tests.manage.store')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::get('/athlete-tests/{test}/edit', 'editTest')->name('athlete-tests.manage.edit')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::put('/athlete-tests/{test}', 'updateTest')->name('athlete-tests.manage.update')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::delete('/athlete-tests/{test}', 'destroyTest')->name('athlete-tests.manage.destroy')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::get('/athlete-tests/user/{user}', 'userReport')->name('athlete-tests.user')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::get('/athlete-tests/user/{user}/create', 'createResult')->name('athlete-tests.create')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::post('/athlete-tests/user/{user}', 'storeResult')->name('athlete-tests.store')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
});
Route::post('/allUsers/{id}/documents', [\App\Http\Controllers\AllUsersController::class, 'storeDocuments'])->name('allUsers.documents.store')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
Route::delete('/allUsers/{id}/documents/{doc}', [\App\Http\Controllers\AllUsersController::class, 'deleteDocument'])->name('allUsers.documents.delete')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
Route::get('/allUsers/{id}/documents/{doc}', [\App\Http\Controllers\AllUsersController::class, 'downloadDocument'])->name('allUsers.documents.download')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');

#=======================================================================================#
#                              Schedules Routes                                         #
#=======================================================================================#
Route::controller(\App\Http\Controllers\ScheduleController::class)->group(function () {
    Route::get('/schedules', 'index')->name('schedules.index')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::get('/schedules/create', 'create')->name('schedules.create')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::post('/schedules', 'store')->name('schedules.store')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::get('/schedules/{schedule}/edit', 'edit')->name('schedules.edit')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::put('/schedules/{schedule}', 'update')->name('schedules.update')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
    Route::delete('/schedules/{schedule}', 'destroy')->name('schedules.destroy')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager|coach');
});

#=======================================================================================#
#                              Roles & Permissions Routes                               #
#=======================================================================================#
Route::controller(RolePermissionController::class)->group(function () {
    Route::get('/roles', 'index')->name('roles.index')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin');
    Route::get('/roles/{role}/users', 'showUsers')->name('roles.users')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin');
    Route::get('/roles/create', 'createRole')->name('roles.create')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin');
    Route::post('/roles', 'storeRole')->name('roles.store')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin');
    Route::get('/roles/{role}/edit', 'editRole')->name('roles.edit')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin');
    Route::post('/roles/{role}/clone', 'cloneRole')->name('roles.clone')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin');
    Route::put('/roles/{role}', 'updateRole')->name('roles.update')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin');
    Route::delete('/roles/{role}', 'destroyRole')->name('roles.destroy')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin');
    Route::get('/permissions/create', 'createPermission')->name('permissions.create')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin');
    Route::post('/permissions', 'storePermission')->name('permissions.store')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin');
    Route::delete('/permissions/{permission}', 'destroyPermission')->name('permissions.destroy')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin');
});

#=======================================================================================#
#                                   Staff Management Routes                             #
#=======================================================================================#
Route::controller(StaffController::class)->group(function () {
    Route::get('/staff', 'index')->name('staff.index')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::get('/staff/create', 'create')->name('staff.create')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::post('/staff', 'store')->name('staff.store')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::get('/staff/{staff}/edit', 'edit')->name('staff.edit')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::put('/staff/{staff}', 'update')->name('staff.update')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::delete('/staff/{staff}', 'destroy')->name('staff.destroy')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
});

#=======================================================================================#
#                                Moderator Management Routes                            #
#=======================================================================================#
Route::controller(ModeratorController::class)->group(function () {
    Route::get('/moderators', 'index')->name('moderators.index')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::get('/moderators/create', 'create')->name('moderators.create')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::post('/moderators', 'store')->name('moderators.store')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::get('/moderators/{moderator}/edit', 'edit')->name('moderators.edit')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::put('/moderators/{moderator}', 'update')->name('moderators.update')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
    Route::delete('/moderators/{moderator}', 'destroy')->name('moderators.destroy')->middleware('auth')->middleware('logs-out-banned-user')->middleware('role:admin|cityManager|gymManager');
});
