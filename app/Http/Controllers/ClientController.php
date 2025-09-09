<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function dashboard()
    {
        $client = Client::firstOrCreate(
            ['user_id' => Auth::id()],
            ['first_name' => Auth::user()->name ?? '', 'last_name' => '']
        )->load(['bmiRecords' => fn($q) => $q->latest('measured_at')]);

        return view('dashboard', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'gender' => 'nullable',
            'birthdate' => 'nullable|date',
            'height_cm' => 'nullable|integer|min:120|max:250',
            'condition_notes' => 'nullable|string|max:2000',
        ]);
        $client->update($data);
        return back()->with('status', 'Perfil actualizado');
    }
}
