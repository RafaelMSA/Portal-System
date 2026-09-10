<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

// Root route sends guests to login and authenticated users to the dashboard.
Route::get('/', function () {
	return auth()->check()
		? redirect()->route('dashboard')
		: redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

// Admin and Staff routes (redirect through DashboardController to handle role-based views)
Route::get('/admin', [DashboardController::class, 'index'])->middleware('auth')->name('admin');
Route::get('/staff', [DashboardController::class, 'index'])->middleware('auth')->name('staff');

// --- Minimal auth routes for local testing ---
// Use the seeded accounts below: admin@example.test, staff@example.test, student@example.test.
// Change the seed data in database/seeders/DatabaseSeeder.php and the password in database/factories/UserFactory.php.
Route::middleware('guest')->group(function () {
	Route::get('/login', function () {
		return view('auth.login');
	})->name('login');

	Route::post('/login', function (Request $request) {
		$credentials = $request->validate([
			'username' => ['required', 'string'],
			'password' => ['required'],
		]);

		$login = $credentials['username'];
		$loginField = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
		$attempt = Auth::attempt([
			$loginField => $login,
			'password' => $credentials['password'],
		], $request->filled('remember'));

		if ($attempt) {
			$request->session()->regenerate();
			return redirect()->intended(route('dashboard'));
		}

		return back()->withErrors(['username' => 'The provided credentials do not match our records.'])->withInput();
	});
});

Route::get('/forgot-password', function () {
	return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', function (Request $request) {
	$request->validate(['email' => 'required|email']);

	$status = Password::sendResetLink($request->only('email'));

	return $status === Password::RESET_LINK_SENT
		? back()->with('status', __($status))
		: back()->withErrors(['email' => __($status)]);
})->name('password.email');

Route::get('/reset-password/{token}', function ($token) {
	return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');

Route::post('/reset-password', function (Request $request) {
	$validated = $request->validate([
		'token' => 'required',
		'email' => 'required|email',
		'password' => 'required|min:8|confirmed',
	]);

	$status = Password::reset(
		$validated,
		function ($user, $password) {
			$user->password = bcrypt($password);
			$user->save();
		}
	);

	return $status === Password::PASSWORD_RESET
		? redirect()->route('login')->with('status', __($status))
		: back()->withErrors(['email' => [__($status)]]);
})->name('password.update');

Route::post('/logout', function (Request $request) {
	Auth::logout();
	$request->session()->invalidate();
	$request->session()->regenerateToken();
	return redirect()->route('login');
})->name('logout')->middleware('auth');

// Profile routes
Route::middleware(['auth'])->group(function () {
	Route::get('/profile/{user}/edit', [ProfileController::class, 'edit'])->name('profile.edit');
	Route::put('/profile/{user}', [ProfileController::class, 'update'])->name('profile.update');

	Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
	Route::get('/users/create', [App\Http\Controllers\UserController::class, 'create'])->name('users.create');
	Route::post('/users', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');
	Route::get('/users/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
	Route::put('/users/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
	Route::delete('/users/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
});

// Announcements CRUD (for admins and faculty)
Route::middleware(['auth'])->group(function () {
	Route::resource('announcements', AnnouncementController::class);
	Route::resource('achievements', AchievementController::class);

	Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
	Route::get('/messages/create', [MessageController::class, 'create'])->name('messages.create');
	Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
	Route::get('/messages/{message}', [MessageController::class, 'show'])->name('messages.show');
	Route::post('/messages/{message}/reply', [MessageController::class, 'reply'])->name('messages.reply');
	Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
});

