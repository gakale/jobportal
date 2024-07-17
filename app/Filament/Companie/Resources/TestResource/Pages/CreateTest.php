<?php

namespace App\Filament\Companie\Resources\TestResource\Pages;

use App\Filament\Companie\Resources\TestResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\JobPosting;
use App\Models\Question;
use App\Models\TestQuestion;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CreateTest extends CreateRecord
{
    protected static string $resource = TestResource::class;

    protected function beforeCreate(): void
    {
        // Get the form data
        $data = $this->form->getState();

        // Get the authenticated company
        $company = Auth::guard('company')->user();
        if (!$company) {
            Notification::make()
                ->title('Erreur')
                ->body('Entreprise non connectée.')
                ->danger()
                ->send();
            $this->halt();
            return;
        }

        // Log the authenticated company
        Log::info('Authenticated Company: ', $company->toArray());

        // Check subscription status
        if ($company->isOnTrial() && $data['number_of_questions'] > 1) {
            Notification::make()
                ->title('Erreur')
                ->body('En période d\'essai, vous ne pouvez générer qu\'une seule question.')
                ->danger()
                ->send();
            $this->halt();
            return;
        }

        if (!$company->hasActiveSubscription() && !$company->isOnTrial()) {
            Notification::make()
                ->title('Erreur')
                ->body('Vous devez souscrire à un abonnement pour générer des questions.')
                ->danger()
                ->send();
            $this->halt();
            return;
        }

        // Find the job posting associated with this record
        $jobPosting = JobPosting::find($data['job_posting_id']);
        if (!$jobPosting) {
            Notification::make()
                ->title('Erreur')
                ->body('Offre d\'emploi non trouvée.')
                ->danger()
                ->send();
            $this->halt();
            return;
        }

        $this->record = $this->getModel()::make();

        // Set the company_id and other attributes
        $this->record->company_id = $company->id;
        $this->record->job_posting_id = $data['job_posting_id'];
        $this->record->number_of_questions = $data['number_of_questions'];
        $this->record->duration = $data['duration'];
        $this->record->language = $data['language'];
        $this->record->save();

        // Prepare the payload for the API
        $formattedDescription = $jobPosting->formatted_description;
        $numberOfQuestions = $data['number_of_questions'];
        $duration = $data['duration'];
        $language = $data['language'];

        $payload = [
            'text_format' => $formattedDescription,
            'number_of_questions' => $numberOfQuestions,
            'duration' => $duration,
            'language' => $language,
        ];

        // Log the payload sent to the API
        Log::info('Payload sent to API: ', $payload);

        try {
            Log::info('Attempting to send request to API');
            $response = Http::timeout(60)->post('http://127.0.0.1:5000/generate_questions', $payload);
            Log::info('API request sent successfully');

            if ($response->successful()) {
                $questionsData = $response->json()['questions'];

                // Log the response data
                Log::info('Response from API: ', $questionsData);

                foreach ($questionsData as $qData) {
                    // Log data to check
                    Log::info('Creating question with data: ', $qData);

                    // Ensure all necessary fields are provided
                    if (isset($qData['question']) && isset($qData['choices']) && isset($qData['correct_answer']) && isset($qData['time_to_answer'])) {
                        $question = Question::create([
                            'question_text' => $qData['question'],
                            'choices' => json_encode($qData['choices']),
                            'correct_answer' => $qData['correct_answer'],
                            'time_to_answer' => $qData['time_to_answer'],
                            'language' => $language,
                        ]);

                        TestQuestion::create([
                            'test_id' => $this->record->id,
                            'question_id' => $question->id,
                        ]);
                    } else {
                        throw new \Exception('Missing fields in question data: ' . json_encode($qData));
                    }
                }

                Notification::make()
                    ->title('Succès')
                    ->body('Les questions ont été générées avec succès.')
                    ->success()
                    ->send();
            } else {
                throw new \Exception('Erreur lors de la génération des questions.');
            }
        } catch (\Exception $e) {
            Log::error('Failed to send request to API: ' . $e->getMessage());
            Notification::make()
                ->title('Erreur')
                ->body($e->getMessage())
                ->danger()
                ->send();
            $this->halt();
        }
    }
}