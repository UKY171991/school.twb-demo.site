<?php
use App\Models\Grade;
$grades = Grade::all();
foreach ($grades as $g) {
    echo "Grade: '{$g->name}' (ID: {$g->id})\n";
}
