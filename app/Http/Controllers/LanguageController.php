<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LanguageController extends Controller
{
    /**
     * Définir la langue en français
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setFrench(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return back()->with('error', 'Utilisateur non authentifié');
            }

            $user->update(['preferred_language' => 'fr']);

            return back()->with('success', 'Langue mise à jour en français');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour de la langue');
        }
    }

    /**
     * Définir la langue en anglais
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setEnglish(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return back()->with('error', 'User not authenticated');
            }

            $user->update(['preferred_language' => 'en']);

            return back()->with('success', 'Language updated to English');

        } catch (\Exception $e) {
            return back()->with('error', 'Error updating language');
        }
    }
}