<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('telescope:prune --hours=72')->daily();

require __DIR__.'/test.php';
