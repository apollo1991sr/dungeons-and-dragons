<?php

namespace App\Filament\Resources\Items\Pages;

use App\Filament\Resources\Items\ItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;

class EditItem extends EditRecord
{
    protected static string $resource = ItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('printCard')
                ->label('Картка для друку')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->url(
                    fn (): string =>
                    route('items.card', $this->getRecord()->slug)
                )
                ->openUrlInNewTab(),
            DeleteAction::make(),
        ];
    }
}
