<?php

Route::get('/', function() {
    return view('welcome');
    });

Route::resource('tasks', 'TasksController');