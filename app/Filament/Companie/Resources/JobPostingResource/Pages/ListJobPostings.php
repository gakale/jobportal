<?php

namespace App\Filament\Companie\Resources\JobPostingResource\Pages;

use App\Filament\Companie\Resources\JobPostingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;
use App\Filament\Companie\Resources\SubscriptionResource;
class ListJobPostings extends ListRecords
{
    protected static string $resource = JobPostingResource::class;

    protected function getHeaderActions(): array
    {
        $company = Auth::guard('company')->user();
        
        if ($company) {
            $subscription = $company->subscription;
            
            if ($subscription && $subscription->isActive()) {
                return [
                    Actions\CreateAction::make(),
                ];
            } else {
                return [
                    Actions\CreateAction::make('update_subscription')
                        ->label('Update Subscription')
                        ->url(SubscriptionResource::getUrl())
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Update Subscription')
                        ->modalSubheading('Your subscription is not active. Please update your subscription to post a new job.')
                        ->modalButton('Update Subscription')
                        ->action(function () {
                            Notification::make()
                                ->title('Update Subscription')
                                ->body('Please update your subscription to post a new job.')
                                ->success()
                                ->send();
                        }),
                ];
            }
        }

        return [];
    }
}
