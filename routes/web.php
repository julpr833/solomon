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
Route::get('/registrarse', [UsersController::class, 'signup'])->name('signup');
Route::get('/ingresar', [UsersController::class, 'login'])->name('login');
Route::post('/cerrar-sesion', [UsersController::class, 'logout'])->name('logout'); // Generalmente es POST por seguridad

// --- Rutas de Configuración ---
Route::get('/configuracion', [SettingsController::class, 'settings'])->name('settings');

// --- Rutas de Hábitos ---
Route::get('/dashboard', [HabitsController::class, 'dashboard'])->name('dashboard');
Route::get('/habito/{id}', [HabitsController::class, 'show'])->name('habit');
Route::post('/habito/crear', [HabitsController::class, 'create'])->name('habit.create');
Route::patch('/habito/editar', [HabitsController::class, 'edit'])->name('habit.edit');
Route::delete('/habito/eliminar', [HabitsController::class, 'delete'])->name('habit.delete');

// --- Rutas de Metas ---
Route::get('/metas', [GoalsController::class, 'index'])->name('goals');
Route::post('/metas/crear', [GoalsController::class, 'create'])->name('goals.create');
Route::patch('/metas/editar', [GoalsController::class, 'edit'])->name('goals.edit');
Route::delete('/metas/eliminar', [GoalsController::class, 'delete'])->name('goals.delete');

// --- Rutas de Recompensas ---
Route::post('/recompensas/crear', [RewardsController::class, 'create'])->name('reward.create');
Route::patch('/recompensas/editar', [RewardsController::class, 'edit'])->name('reward.edit');
Route::delete('/recompensas/eliminar', [RewardsController::class, 'delete'])->name('reward.delete');

