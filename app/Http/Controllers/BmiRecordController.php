<?php

namespace App\Http\Controllers;

use App\Models\BmiRecord;
use App\Models\Client;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BmiRecordController extends Controller
{
    public function __construct(private RecommendationService $svc) {}

    public function index()
    {
        $client = Client::where('user_id', Auth::id())->firstOrFail();
        $records = $client->bmiRecords()->latest('measured_at')->paginate(10);
        return view('bmi.index', compact('client', 'records'));
    }

    public function store(Request $request)
    {
        $client = Client::where('user_id', Auth::id())->firstOrFail();

        $data = $request->validate([
            'weight_kg' => 'required|numeric|min:20|max:400',
            'height_cm' => 'nullable|integer|min:120|max:250',
            'measured_at' => 'nullable|date'
        ]);

        $height = $data['height_cm'] ?? $client->height_cm;
        if (!$height) {
            return back()->withErrors(['height_cm' => 'Debes registrar la estatura.']);
        }

        $bmi = $this->svc->computeBmi($data['weight_kg'], $height);
        $category = $this->svc->bmiCategory($bmi);
        $age = $client->age;
        $gender = $client->gender;
        $sugg = $this->svc->suggest($age, $gender, $bmi, (string) $client->condition_notes);

        BmiRecord::create([
            'client_id' => $client->id,
            'weight_kg' => $data['weight_kg'],
            'height_cm' => $height,
            'bmi' => $bmi,
            'bmi_category' => $category,
            'diet_suggestion' => $sugg['dieta'],
            'routine_suggestion' => $sugg['rutina'],
            'measured_at' => $data['measured_at'] ?? now(),
        ]);

        if (isset($data['height_cm'])) {
            $client->update(['height_cm' => $height]);
        }

        return redirect()
            ->route('bmi.index')
            ->with('status', 'IMC registrado');
    }
}
