<?php

/**
 * Central function for displaying dates.
 * All date display in views should go through this.
 * Stores in UTC, displays in user's timezone.
 */
function usertime($date, string $format = null): \Carbon\Carbon|string
{
    $carbon = \Carbon\Carbon::parse($date ?? now())->setTimezone('UTC');

    if (auth()->check() && auth()->user()->timezone) {
        $carbon = $carbon->setTimezone(auth()->user()->timezone);
    }

    return $format ? $carbon->format($format) : $carbon;
}
