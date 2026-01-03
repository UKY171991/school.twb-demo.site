<?php

namespace App\Console\Commands;

use App\Models\Marksheet;
use Illuminate\Console\Command;

class RecalculateClassPositions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'marksheet:recalculate-positions {--class=} {--section=} {--academic-year=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate class positions for all marksheets';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting class position recalculation...');

        $query = Marksheet::query();

        // Apply filters if provided
        if ($this->option('class')) {
            $query->where('class', $this->option('class'));
        }

        if ($this->option('section')) {
            $query->where('section', $this->option('section'));
        }

        if ($this->option('academic-year')) {
            $query->where('academic_year', $this->option('academic-year'));
        }

        // Group marksheets by class, section, exam_type, and academic year
        $marksheets = $query->get();

        $groups = $marksheets->groupBy(function ($marksheet) {
            return $marksheet->class.'-'.$marksheet->section.'-'.
                   ($marksheet->exam_type_id ?? 'no-exam-type').'-'.
                   $marksheet->academic_year;
        });

        $totalGroups = $groups->count();
        $processedGroups = 0;

        foreach ($groups as $groupKey => $groupMarksheets) {
            $processedGroups++;

            // Sort by percentage (descending) and obtained marks (descending)
            $sortedMarksheets = $groupMarksheets->sortByDesc('percentage')
                ->sortByDesc('obtained_marks');

            $totalStudents = $sortedMarksheets->count();
            $position = 1;

            foreach ($sortedMarksheets as $marksheet) {
                $marksheet->update([
                    'class_position' => $position,
                    'total_students' => $totalStudents,
                ]);
                $position++;
            }

            $this->info("Processed group {$processedGroups}/{$totalGroups}: {$groupKey} ({$totalStudents} students)");
        }

        $this->info('Class position recalculation completed!');
        $this->info("Total groups processed: {$totalGroups}");
        $this->info("Total marksheets updated: {$marksheets->count()}");

        return 0;
    }
}
