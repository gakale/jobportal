<?php

namespace App\Filament\Companie\Resources\SubscriptionResource\Pages;

use App\Filament\Companie\Resources\SubscriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubscription extends EditRecord
{
    protected static string $resource = SubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->action(function () {
                    $this->record->update(['ends_at' => now()]);
                    $this->notify('success', 'Abonnement annulé avec succès.');
                })
                ->requiresConfirmation(),
        ];
    }
}
