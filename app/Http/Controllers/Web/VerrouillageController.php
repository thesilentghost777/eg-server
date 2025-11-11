<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ReceptionPointeur;
use App\Models\RetourProduit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerrouillageController extends Controller
{
    public function index()
    {
        $datesReceptions = ReceptionPointeur::selectRaw('DATE(date_reception) as date')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get()
            ->pluck('date');

        $datesRetours = RetourProduit::selectRaw('DATE(date_retour) as date')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get()
            ->pluck('date');

        $dates = $datesReceptions->merge($datesRetours)->unique()->sort()->reverse()->values();

        $stats = [];
        foreach ($dates as $date) {
            $receptions = ReceptionPointeur::whereDate('date_reception', $date)->get();
            $retours = RetourProduit::whereDate('date_retour', $date)->get();

            $stats[$date] = [
                'receptions_count' => $receptions->count(),
                'receptions_verrouillees' => $receptions->where('verrou', true)->count(),
                'retours_count' => $retours->count(),
                'retours_verrouilles' => $retours->where('verrou', true)->count(),
                'tout_verrouille' => $receptions->every('verrou') && $retours->every('verrou') && ($receptions->count() > 0 || $retours->count() > 0)
            ];
        }

        return view('verrouillage.index', compact('dates', 'stats'));
    }

    public function verrouiller(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:receptions,retours,tous'
        ]);

        DB::beginTransaction();
        try {
            if (in_array($validated['type'], ['receptions', 'tous'])) {
                ReceptionPointeur::whereDate('date_reception', $validated['date'])
                    ->update(['verrou' => true]);
            }

            if (in_array($validated['type'], ['retours', 'tous'])) {
                RetourProduit::whereDate('date_retour', $validated['date'])
                    ->update(['verrou' => true]);
            }

            DB::commit();
            return redirect()->route('verrouillage.index')->with('success', 'Verrouillage effectué avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('verrouillage.index')->with('error', 'Erreur lors du verrouillage.');
        }
    }

    public function deverrouiller(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:receptions,retours,tous'
        ]);

        DB::beginTransaction();
        try {
            if (in_array($validated['type'], ['receptions', 'tous'])) {
                ReceptionPointeur::whereDate('date_reception', $validated['date'])
                    ->update(['verrou' => false]);
            }

            if (in_array($validated['type'], ['retours', 'tous'])) {
                RetourProduit::whereDate('date_retour', $validated['date'])
                    ->update(['verrou' => false]);
            }

            DB::commit();
            return redirect()->route('verrouillage.index')->with('success', 'Déverrouillage effectué avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('verrouillage.index')->with('error', 'Erreur lors du déverrouillage.');
        }
    }
}
