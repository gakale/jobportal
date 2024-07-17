<?php
namespace App\Filament\Companie\Resources\SubscriptionResource\Pages;

use App\Filament\Companie\Resources\SubscriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class ListSubscriptions extends ListRecords
{
    protected static string $resource = SubscriptionResource::class;

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
                        ->modalSubheading('Your subscription is not active. Please update your subscription to manage your subscriptions.')
                        ->modalButton('Update Subscription')
                        ->action(function () {
                            Notification::make()
                                ->title('Update Subscription')
                                ->body('Please update your subscription to manage your subscriptions.')
                                ->success()
                                ->send();
                        }),
                ];
            }
        }

        return [];
    }

    protected function getTableQuery(): ?Builder
    {
        $company = Auth::guard('company')->user();

        if ($company) {
            return parent::getTableQuery()->where('company_id', $company->id);
        }

        return parent::getTableQuery();
    }
}
