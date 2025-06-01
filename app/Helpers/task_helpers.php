<?php

if (!function_exists('translateStatus')) {
    function translateStatus(string $status): string
    {
        return match ($status) {
            'to-do' => 'Do zrobienia',
            'in-progress' => 'W trakcie',
            'done' => 'Zrobione',
            default => ucfirst($status),
        };
    }
}

if (!function_exists('translatePriority')) {
    function translatePriority(string $priority): string
    {
        return match ($priority) {
            'low' => 'Niski',
            'medium' => 'Średni',
            'high' => 'Wysoki',
            default => ucfirst($priority),
        };
    }
}
