<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\DB;

Schedule::command('telescope:prune --hours=72')->daily();
Schedule::call(fn() => DB::table('user_activities')->where('created_at', '<', now()->subDays(90))->delete())->daily();
