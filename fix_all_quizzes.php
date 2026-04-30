<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (\App\Models\Quiz::all() as $quiz) {
    $questions = $quiz->questions()->get();
    if ($questions->count() > 0) {
        $sumOriginal = $questions->sum('points');
        $nouvelleNoteMax = $quiz->note_max;

        if ($sumOriginal !== $nouvelleNoteMax && $sumOriginal > 0) {
            echo "Fixing quiz {$quiz->id} - {$quiz->titre}\n";
            
            if ($nouvelleNoteMax < $questions->count()) {
                $nouvelleNoteMax = $questions->count();
                $quiz->update(['note_max' => $nouvelleNoteMax]);
            }

            $mapped = $questions->map(function ($q) use ($sumOriginal, $nouvelleNoteMax) {
                $exact = ($q->points / $sumOriginal) * $nouvelleNoteMax;
                $floor = floor($exact);
                return [
                    'question' => $q,
                    'exact' => $exact,
                    'floor' => $floor,
                    'remainder' => $exact - $floor
                ];
            });

            $sumFloor = $mapped->sum('floor');
            $diff = $nouvelleNoteMax - $sumFloor;

            $mapped = $mapped->sortByDesc('remainder')->values();

            foreach ($mapped as $index => $item) {
                $finalPoints = $item['floor'] + ($index < $diff ? 1 : 0);
                if ($finalPoints < 1) $finalPoints = 1;
                $item['question']->update(['points' => $finalPoints]);
            }
            
            $finalSum = $quiz->questions()->sum('points');
            if ($finalSum > $nouvelleNoteMax) {
                $diffAmount = $finalSum - $nouvelleNoteMax;
                $largest = $quiz->questions()->orderBy('points', 'desc')->get();
                foreach ($largest as $lq) {
                    if ($diffAmount > 0 && $lq->points > 1) {
                        $reduction = min($diffAmount, $lq->points - 1);
                        $lq->update(['points' => $lq->points - $reduction]);
                        $diffAmount -= $reduction;
                    }
                }
            }
            
            $finalSum = $quiz->questions()->sum('points');
            if ($finalSum < $nouvelleNoteMax) {
                $first = $quiz->questions()->first();
                $first->update(['points' => $first->points + ($nouvelleNoteMax - $finalSum)]);
            }
        }
    }
}
echo "Done\n";
