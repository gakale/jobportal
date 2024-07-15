<?php

namespace App\Filament\Companie\Resources\JobPostingResource\Pages;

use App\Filament\Companie\Resources\JobPostingResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Http;
use App\Models\JobPosting;
use Filament\Notifications\Notification;

class CreateJobPosting extends CreateRecord
{
    protected static string $resource = JobPostingResource::class;

    protected function afterCreate(): void
    {
        // Récupérer les données de l'enregistrement
        $jobPosting = $this->record;

        // Convertir la date limite en instance de DateTime si nécessaire
        $deadline = is_string($jobPosting->deadline) ? new \DateTime($jobPosting->deadline) : $jobPosting->deadline;

        // Préparer les données pour l'API Python
        $payload = [
            'company_id' => $jobPosting->company_id,
            'company_name' => $jobPosting->company->name,
            'title' => $jobPosting->title,
            'description' => $jobPosting->description,
            'location' => $jobPosting->location,
            'salary' => $jobPosting->salary,
            'keywords' => explode(',', $jobPosting->keywords),
            'score_threshold' => $jobPosting->score_threshold,
            'application_link' => $jobPosting->application_link,
            'deadline' => $deadline->format('Y-m-d'),
        ];

        // Envoyer les données à l'API Python
        $response = Http::post('http://127.0.0.1:5000/generate_job_posting', $payload);

        if ($response->successful()) {
            // Récupérer le texte formaté de l'offre d'emploi
            $formattedDescription = $response->json()['job_posting'];

            // Mettre à jour l'enregistrement avec le texte formaté
            $jobPosting->update(['formatted_description' => $formattedDescription]);

            // Envoyer une notification à l'utilisateur
            Notification::make()
                ->title('Succès')
                ->body('L\'offre d\'emploi a été créée avec succès.')
                ->success()
                ->send();
        } else {
            // Gérer l'erreur de l'API Python
            Notification::make()
                ->title('Erreur')
                ->body('Une erreur est survenue lors de la génération de l\'offre d\'emploi.')
                ->danger()
                ->send();
        }
    }
}
