<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('taskmaster');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard');
    Route::get('/dashboard', [TodoController::class, 'dashboard'])->name('dashboard');

    Route::post('/create/todo', [TodoController::class, 'createTodo'])->name('create.todo');
    Route::get('/fetch/todo', [TodoController::class, 'fetchTodo'])->name('fetch.todo');
    Route::patch('/update/todo/{id}', [TodoController::class, 'updateTodo'])->name('update.todo');
    Route::patch('/remove/todo/{id}', [TodoController::class, 'removeTodo'])->name('remove.todo');
    Route::post('/filter/todos', [TodoController::class, 'filterTodo'])->name('filter.Todo');

});
