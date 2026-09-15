<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\HabitsController;
use App\Http\Controllers\GoalsController;
use App\Http\Controllers\RewardsController;

// --- Rutas de Inicio ---
// Redirigimos /index y /home a la raíz (/) o apuntamos todas al mismo método
Route::get('/', [UsersController::class, 'home'])->name('home');
Route::get('/index', [UsersController::class, 'home']);
Route::get('/home', [UsersController::class, 'home']);

// --- Rutas de Usuarios ---
Route::get('/registrarse', [UsersController::class, 'signup'])->name('signup')->middleware('guest');
Route::post('/registrarse', [UsersController::class, 'signUpStore'])->name('signup')->middleware('guest');
Route::get('/ingresar', [UsersController::class, 'login'])->name('login')->middleware('guest');
Route::post('/ingresar', [UsersController::class, 'loginStore'])->name('login.store')->middleware('guest');
Route::post('/cerrar-sesion', [UsersController::class, 'logout'])->name('logout')->middleware('auth'); // Generalmente es POST por seguridad

// --- Rutas de Configuración ---
Route::get('/configuracion', [SettingsController::class, 'settings'])->name('settings')->middleware('auth');

// --- Rutas de Hábitos ---
Route::get('/dashboard', [HabitsController::class, 'dashboard'])->name('dashboard')->middleware('auth');
Route::get('/habito/{id}', [HabitsController::class, 'show'])->name('habit')->middleware('auth');
Route::post('/habito/crear', [HabitsController::class, 'create'])->name('habit.create')->middleware('auth');
Route::patch('/habito/editar', [HabitsController::class, 'edit'])->name('habit.edit')->middleware('auth');
Route::delete('/habito/eliminar', [HabitsController::class, 'delete'])->name('habit.delete')->middleware('auth');

// --- Rutas de Metas ---
Route::get('/metas', [GoalsController::class, 'index'])->name('goals')->middleware('auth');
Route::post('/metas/crear', [GoalsController::class, 'create'])->name('goals.create')->middleware('auth');
Route::patch('/metas/editar', [GoalsController::class, 'edit'])->name('goals.edit')->middleware('auth');
Route::delete('/metas/eliminar', [GoalsController::class, 'delete'])->name('goals.delete')->middleware('auth');

// --- Rutas de Recompensas ---
Route::post('/recompensas/crear', [RewardsController::class, 'create'])->name('reward.create')->middleware('auth');
Route::patch('/recompensas/editar', [RewardsController::class, 'edit'])->name('reward.edit')->middleware('auth');
Route::delete('/recompensas/eliminar', [RewardsController::class, 'delete'])->name('reward.delete')->middleware('auth');
