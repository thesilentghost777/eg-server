<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\RetourProduit;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Http\Request;

class RetourProduitController extends Controller
{
    public function index()
    {
        $retours = RetourProduit::with(['pointeur', 'vendeur', 'produit'])
            ->orderBy('date_retour', 'desc')
            ->paginate(20);

        return view('retours.index', compact('retours'));
    }

    public function show(RetourProduit $retour)
    {
        $retour->load(['pointeur', 'vendeur', 'produit']);
        return view('retours.show', compact('retour'));
    }

    public function edit(RetourProduit $retour)
    {
        if ($retour->verrou) {
            return redirect()->route('retours.index')->with('error', 'Ce retour est verrouillé et ne peut pas être modifié.');
        }

        $produits = Produit::where('actif', true)->get();
        $pointeurs = User::where('role', 'pointeur')->where('actif', true)->get();
        $vendeurs = User::whereIn('role', ['vendeur_boulangerie', 'vendeur_patisserie'])->where('actif', true)->get();

        return view('retours.edit', compact('retour', 'produits', 'pointeurs', 'vendeurs'));
    }

    public function update(Request $request, RetourProduit $retour)
    {
        if ($retour->verrou) {
            return redirect()->route('retours.index')->with('error', 'Ce retour est verrouillé et ne peut pas être modifié.');
        }

        $validated = $request->validate([
            'pointeur_id' => 'required|exists:users,id',
            'vendeur_id' => 'required|exists:users,id',
            'produit_id' => 'required|exists:produits,id',
            'quantite' => 'required|integer|min:1',
            'raison' => 'required|in:perime,abime,autre',
            'description' => 'nullable|string|max:1000',
            'date_retour' => 'required|date',
        ]);

        $retour->update($validated);

        return redirect()->route('retours.index')->with('success', 'Retour mis à jour avec succès.');
    }

    public function destroy(RetourProduit $retour)
    {
        if ($retour->verrou) {
            return redirect()->route('retours.index')->with('error', 'Ce retour est verrouillé et ne peut pas être supprimé.');
        }

        $retour->delete();

        return redirect()->route('retours.index')->with('success', 'Retour supprimé avec succès.');
    }
}
