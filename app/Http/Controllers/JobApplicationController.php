<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidature;
use App\Models\Candidat;
use App\Models\JobPosting;
use Illuminate\Support\Facades\Http;

class JobApplicationController extends Controller
{
    public function apply(Request $request, JobPosting $job)
    {
        // Valider l'entrée
        $request->validate([
            'cv' => 'required|mimes:pdf|max:2048',
            'cover_letter' => 'nullable|mimes:pdf|max:2048',
        ]);

        // Téléchargement du CV et de la lettre de motivation (si fourni)
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cvs');
        } else {
            return redirect()->back()->withErrors(['error' => 'Le CV est requis.']);
        }

        $coverLetterPath = $request->hasFile('cover_letter') ? $request->file('cover_letter')->store('cover_letters') : null;

        // Envoi du CV et d'autres données à l'API Flask
        $response = Http::attach('cv', file_get_contents(storage_path('app/' . $cvPath)), 'cv.pdf')
                        ->attach('cover_letter', $coverLetterPath ? file_get_contents(storage_path('app/' . $coverLetterPath)) : '', 'cover_letter.pdf')
                        ->post('http://127.0.0.1:5000/analyze', [
                            'job_description' => $job->description,
                            'job_keywords' => $job->keywords,
                        ]);

        // Vérifiez si l'appel API a réussi
        if ($response->successful()) {
            // Obtenez le score et l'analyse de la réponse de l'API
            $score = $response->json()['score'];
            $analysis = $response->json()['analysis'];
            $analysis = substr($response->json()['analysis'], 0, 255);  // Limiter à 255 caractères

            // Enregistrer la candidature dans la base de données
            Candidature::create([
                'candidat_id' => null, // Puisqu'aucune connexion n'est requise, vous pouvez laisser cela comme nul ou l'assigner plus tard
                'job_posting_id' => $job->id,
                'status' => 'pending',
                'score_threshold' => $score,
                'comment' => $analysis,
            ]);

            // Rediriger avec un message de succès
            return redirect()->back()->with('success', 'Votre candidature a été soumise avec succès!');
        }

        // Gérer l'échec
        return redirect()->back()->withErrors(['error' => 'Une erreur est survenue lors de la soumission de votre candidature.']);
    }
}
