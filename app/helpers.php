<?php

if (!function_exists('highlightText')) {
    function highlightText($text, $search) {
        if (!$search) return $text;

        $highlighted = preg_replace(
            "/(" . preg_quote($search) . ")/i",
            "<span class=\"bg-yellow-300 text-gray-800 px-1 rounded\">$1</span>",
            $text
        );

        return $highlighted ?: $text;
    }
}
