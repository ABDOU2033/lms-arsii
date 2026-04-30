<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';
echo "QUIZZES:\n";
foreach(\App\Models\Quiz::all() as $q){
    echo 'ID'.$q->id.' - '.$q->titre.' - Questions:'.$q->questions->count()."\n";
}
