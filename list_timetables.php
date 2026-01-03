<?php

use App\Models\ExamTimetable;

$timetables = ExamTimetable::all();
echo 'Total Timetables: '.$timetables->count()."\n";

foreach ($timetables->unique('class') as $t) {
    echo "Class: '{$t->class}', SchoolID: {$t->school_id}\n";
}
