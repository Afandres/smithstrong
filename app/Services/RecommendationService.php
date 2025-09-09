<?php

namespace App\Services;

class RecommendationService
{
    public function computeBmi(float $weightKg, int $heightCm): float
    {
        $m = $heightCm / 100;
        return round($weightKg / ($m * $m), 2);
    }

    public function bmiCategory(float $bmi): string
    {
        return match (true) {
            $bmi < 18.5 => 'Bajo peso',
            $bmi < 25   => 'Normal',
            $bmi < 30   => 'Sobrepeso',
            $bmi < 35   => 'Obesidad I',
            $bmi < 40   => 'Obesidad II',
            default     => 'Obesidad III',
        };
    }

    public function suggest(?int $age, ?string $gender, float $bmi, string $conditionNotes = ''): array
    {
        $flags   = $this->extractFlags($conditionNotes);
        $diet    = $this->dietByProfile($age, $gender, $bmi, $flags);
        $routine = $this->routineByProfile($age, $gender, $bmi, $flags);

        // claves en español
        return ['dieta' => $diet, 'rutina' => $routine];
    }

    protected function extractFlags(string $notes): array
    {
        $n = mb_strtolower($notes);
        return [
            'hypertension' => str_contains($n, 'hipertens') || str_contains($n, 'hta'),
            'diabetes'     => str_contains($n, 'diabet'),
            'knee_issue'   => str_contains($n, 'rodilla') || str_contains($n, 'menisco'),
            'back_pain'    => str_contains($n, 'espalda') || str_contains($n, 'lumbar'),
            'pregnant'     => str_contains($n, 'embaraz'),
            'sedentary'    => str_contains($n, 'sedentar') || str_contains($n, 'poca actividad'),
        ];
    }

    protected function dietByProfile(?int $age, ?string $gender, float $bmi, array $flags): array
    {
        $base = [
            'calorias' => 2000,
            'macros'   => ['proteina' => 25, 'carbohidratos' => 45, 'grasa' => 30],
            'notas'    => ['Hidrátate 2L/día', '5 porciones de frutas/verduras']
        ];

        if ($bmi < 18.5) { // subir peso
            $base['calorias'] = 2300;
            $base['macros'] = ['proteina' => 20, 'carbohidratos' => 55, 'grasa' => 25];
            $base['notas'][] = 'Añade snacks calóricos saludables (frutos secos, yogur griego)';
        } elseif ($bmi < 25) { // mantenimiento
            $base['calorias'] = 2000;
        } elseif ($bmi < 30) { // déficit ligero
            $base['calorias'] = 1800;
            $base['macros'] = ['proteina' => 30, 'carbohidratos' => 40, 'grasa' => 30];
            $base['notas'][] = 'Déficit suave de ~300 kcal';
        } else { // >30
            $base['calorias'] = 1600;
            $base['macros'] = ['proteina' => 30, 'carbohidratos' => 35, 'grasa' => 35];
            $base['notas'][] = 'Déficit moderado de ~500 kcal';
        }

        if ($age !== null && $age >= 55) {
            $base['macros']['proteina'] = max($base['macros']['proteina'], 30);
            $base['notas'][] = 'Prioriza proteína magra para preservar masa muscular';
        }
        if ($flags['hypertension']) $base['notas'][] = 'Reduce sodio (<2g/día), evita ultraprocesados';
        if ($flags['diabetes'])     $base['notas'][] = 'Prioriza carbohidratos complejos y fibra';
        if ($flags['pregnant'])     $base['notas'][] = 'Aumenta ~300 kcal y ácido fólico';

        return $base;
    }

    protected function routineByProfile(?int $age, ?string $gender, float $bmi, array $flags): array
    {
        $routine = [
            'dias_por_semana' => 4,
            'sesiones' => [
                ['nombre' => 'Cardio moderado', 'duracion_min' => 25],
                ['nombre' => 'Fuerza cuerpo completo', 'duracion_min' => 30],
            ],
            'notas' => ['Calentamiento 8–10 min', 'Estiramientos 5–8 min al finalizar']
        ];

        if ($bmi < 18.5) {
            $routine['dias_por_semana'] = 3;
            $routine['notas'][] = 'Enfoca en fuerza + superávit calórico';
        } elseif ($bmi < 25) {
            // baseline
        } elseif ($bmi < 30) {
            $routine['dias_por_semana'] = 5;
            $routine['sesiones'][0]['duracion_min'] = 35;
            $routine['notas'][] = 'Añade HIIT 1 día/semana si es seguro';
        } else {
            $routine['dias_por_semana'] = 5;
            $routine['sesiones'][0]['nombre'] = 'Cardio bajo impacto (elíptica/bici/caminar)';
            $routine['sesiones'][0]['duracion_min'] = 40;
            $routine['notas'][] = 'Progresión de volumen gradual (≈10%/semana)';
        }

        if ($age !== null && $age >= 55) {
            $routine['notas'][] = 'Prioriza movilidad y estabilidad';
        }
        if ($flags['knee_issue']) {
            $routine['notas'][] = 'Evita saltos/sentadillas profundas; usa bici/elíptica';
        }
        if ($flags['back_pain']) {
            $routine['notas'][] = 'Core isométrico (planchas), evita pesos muertos pesados';
        }
        if ($flags['hypertension']) {
            $routine['notas'][] = 'Evita maniobra de Valsalva; descansos 60–90 s';
        }

        return $routine;
    }
}
