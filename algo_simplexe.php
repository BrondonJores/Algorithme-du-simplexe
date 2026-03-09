<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__.'/error.log');
header('Content-Type: application/json');

// =====================
// Récupération POST
// =====================
$numVars   = intval($_POST['numVars'] ?? 0);
$numConstr = intval($_POST['numConstr'] ?? 0);
$optType   = $_POST['optType'] ?? 'max';

// Fonction objectif
$c = [];
for ($i = 1; $i <= $numVars; $i++) {
    $c[] = floatval($_POST["obj_x$i"] ?? 0);
}

// Contraintes
$A = [];
$b = [];
for ($j = 1; $j <= $numConstr; $j++) {
    $row = [];
    for ($i = 1; $i <= $numVars; $i++) {
        $row[] = floatval($_POST["c{$j}{$i}"] ?? 0);
    }
    $A[] = $row;
    $b[] = floatval($_POST["rhs$j"] ?? 0);
}

// =====================
// Ajout des variables d’écart (≤ seulement)
// =====================
for ($i = 0; $i < $numConstr; $i++) {
    for ($k = 0; $k < $numConstr; $k++) {
        $A[$k][] = ($i === $k) ? 1 : 0;
    }
    $c[] = 0;
}

// S’assurer que toutes les lignes ont le même nombre de colonnes
$numTotalVars = count($c);
for ($i = 0; $i < $numConstr; $i++) {
    for ($j = 0; $j < $numTotalVars; $j++) {
        if(!isset($A[$i][$j])) $A[$i][$j] = 0;
    }
}

// =====================
// Initialisation variables de base
// =====================
$base = [];
for ($i = 0; $i < $numConstr; $i++) $base[] = 'E'.($i+1);

// =====================
// Construire tableau initial avec entête
// =====================
$header = array_merge(
    ['Base'],
    array_map(fn($i)=>"x".($i+1), range(0,$numVars-1)),
    array_map(fn($i)=>"E".($i+1), range(0,$numConstr-1)),
    ['2nd membre','Ratio']
);

$table = [];
for ($i = 0; $i < $numConstr; $i++) {
    $row = [$base[$i]];
    for ($j = 0; $j < $numTotalVars; $j++) $row[] = $A[$i][$j];
    $row[] = $b[$i]; // 2nd membre
    $row[] = $b[$i]; // Ratio initial
    $table[] = $row;
}

// Ligne Z (corrigée)
$Zcoeffs = $c;

// Si on est en minimisation, on inverse les signes
if ($optType === 'min') {
    $Zcoeffs = array_map(fn($v) => -$v, $Zcoeffs);
}

// Compléter avec des zéros pour les variables d’écart
while (count($Zcoeffs) < $numTotalVars) {
    $Zcoeffs[] = 0;
}

// Ligne complète Z : "Z" + coefficients + 2 colonnes finales (2nd membre et Ratio)
$Zrow = array_merge(['Z'], $Zcoeffs, [0, 0]);
$table[] = $Zrow;

// Ajouter étape initiale avec entête
$steps = [];
$steps[] = ['table'=>array_merge([$header], $table), 'desc'=>'Tableau initial'];

// =====================
// Simplexe itératif
// =====================
$done = false;
while (!$done) {
    $lastRow = end($table);
    $pivotCol = -1;
    $extreme = ($optType === 'max') ? 0 : INF;

    // Choisir colonne pivot
    for ($j = 1; $j < count($lastRow)-2; $j++) {
        if (($optType === 'max' && $lastRow[$j] > $extreme) ||
            ($optType === 'min' && $lastRow[$j] < $extreme)) {
            $extreme = $lastRow[$j];
            $pivotCol = $j;
        }
    }

    // Si optimal
    if (($optType === 'max' && $extreme <= 0) || ($optType === 'min' && $extreme >= 0)) {
        $done = true; break;
    }

    // Ratio test
    $pivotRow = -1; $minRatio = INF;
    for ($i = 0; $i < count($table)-1; $i++) {
        if ($table[$i][$pivotCol] > 0) {
            $ratio = $table[$i][count($table[$i])-2] / $table[$i][$pivotCol];
            if ($ratio < $minRatio) { $minRatio = $ratio; $pivotRow = $i; }
        }
    }

    if ($pivotRow === -1) { $done = true; break; } // Illimité

    $pivotValue = $table[$pivotRow][$pivotCol];
    // Variable qui entre (colonne du pivot)
    $pivotEntering = $header[$pivotCol];

    // Variable qui sort (ligne du pivot avant mise à jour)
    $pivotLeaving = $table[$pivotRow][0];

    // Valeur du pivot
    $pivotValue = round($table[$pivotRow][$pivotCol], 4);

    // Normalisation pivot
    for ($j = 1; $j < count($table[$pivotRow]); $j++) $table[$pivotRow][$j] /= $pivotValue;

    // Mettre à jour la base
    $table[$pivotRow][0] = $header[$pivotCol];

    // Elimination pivot
    for ($i = 0; $i < count($table); $i++) {
        if ($i === $pivotRow) continue;
        $factor = $table[$i][$pivotCol];
        for ($j = 1; $j < count($table[$i]); $j++) $table[$i][$j] -= $factor * $table[$pivotRow][$j];
    }
    $desc = "Itération " . count($steps) . " : La variable {$pivotEntering} entre dans la base, "
      . "et la variable {$pivotLeaving} en sort."
      . "Le pivot choisi est {$pivotValue} situé à l’intersection de la colonne {$pivotEntering} "
      . "et de la ligne {$pivotLeaving}."
      . "On mettra à jour la base en remplaçant {$pivotLeaving} par {$pivotEntering}.";


    // Ajouter étape
    $steps[] = ['table'=>array_merge([$header], $table),
                'desc'=>$desc];
}

// =====================
// Solution finale
// =====================
$solution = [];
for ($i = 0; $i < $numConstr; $i++) {
    $solution[$table[$i][0]] = $table[$i][count($table[$i])-2];
}
$solution['Z'] = ($table[count($table)-1][count($table[0])-2]*-1);

// =====================
// Envoyer JSON
// =====================
echo json_encode(['steps'=>$steps,'solution'=>$solution], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
exit;
?>
