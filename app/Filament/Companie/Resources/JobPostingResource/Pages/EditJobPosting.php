<?php

namespace App\Filament\Companie\Resources\JobPostingResource\Pages;

use App\Filament\Companie\Resources\JobPostingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;
use App\Filament\Companie\Resources\SubscriptionResource;
use Filament\Notifications\Notification;
use Filament\Forms;
use Filament\Forms\Form;

class EditJobPosting extends EditRecord
{
    protected static string $resource = JobPostingResource::class;

    protected function getHeaderActions(): array
    {
        $company = Auth::guard('company')->user();

        if ($company) {
            $subscription = $company->subscription;

            if ($subscription && $subscription->isActive()) {
                return [
                    Actions\DeleteAction::make(),
                ];
            } else {
                return [
                    Actions\CreateAction::make('update_subscription')
                        ->label('Update Subscription')
                        ->url(SubscriptionResource::getUrl())
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Update Subscription')
                        ->modalSubheading('Your subscription is not active. Please update your subscription to manage your job postings.')
                        ->modalButton('Update Subscription')
                        ->action(function () {
                            Notification::make()
                                ->title('Update Subscription')
                                ->body('Please update your subscription to manage your job postings.')
                                ->success()
                                ->send();
                        }),
                ];
            }
        }

        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Information')
                ->schema([
                    Forms\Components\MarkdownEditor::make('formatted_description')
                        ->label('Formatted Description')
                        ->required(),
                ]),
        ]);
    }
}
