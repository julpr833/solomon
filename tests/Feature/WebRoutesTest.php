<?php

use Tests\TestCase;

uses(TestCase::class);

// Pruebas de humo
test('las rutas GET devuelven estado 200', function (string $url) {
    $this->get($url)->assertOk();
})->with([
    '/',
    '/index',
    '/home',
    '/registrarse',
    '/ingresar',
    '/configuracion',
    '/dashboard',
    '/habito/1', // ID de ejemplo
    '/metas',
]);

test('la ruta para cerrar sesion devuelve estado 200', function () {
    $this->post('/cerrar-sesion')->assertOk();
});

// Pruebas de humo para Hábitos
test('las rutas de modificacion de habitos devuelven estado 200', function () {
    $this->post('/habito/crear')->assertOk();
    $this->patch('/habito/editar')->assertOk();
    $this->delete('/habito/eliminar')->assertOk();
});

// Pruebas de humo para Metas
test('las rutas de modificacion de metas devuelven estado 200', function () {
    $this->post('/metas/crear')->assertOk();
    $this->patch('/metas/editar')->assertOk();
    $this->delete('/metas/eliminar')->assertOk();
});

// Pruebas de humo para Recompensas
test('las rutas de modificacion de recompensas devuelven estado 200', function () {
    $this->post('/recompensas/crear')->assertOk();
    $this->patch('/recompensas/editar')->assertOk();
    $this->delete('/recompensas/eliminar')->assertOk();
});
