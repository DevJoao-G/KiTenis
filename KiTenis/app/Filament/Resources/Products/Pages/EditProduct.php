<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('promo')
                ->label(fn () => $this->record->featured_in_carousel ? 'Editar promoção' : 'Adicionar ao carousel')
                ->icon(fn () => $this->record->featured_in_carousel ? 'heroicon-o-tag' : 'heroicon-o-plus-circle')
                ->color(fn () => $this->record->featured_in_carousel ? 'warning' : 'success')
                ->modalHeading(fn () => $this->record->featured_in_carousel ? 'Editar promoção' : 'Adicionar ao carousel de promoções')
                ->form([
                    TextInput::make('discount_percentage')
                        ->label('Desconto (%)')
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(90)
                        ->step(0.01)
                        ->suffix('%'),

                    DateTimePicker::make('promotion_start')
                        ->label('Início da promoção')
                        ->seconds(false),

                    DateTimePicker::make('promotion_end')
                        ->label('Fim da promoção')
                        ->seconds(false),
                ])
                ->fillForm(fn () => [
                    'discount_percentage' => $this->record->discount_percentage ?? 0,
                    'promotion_start' => $this->record->promotion_start,
                    'promotion_end' => $this->record->promotion_end,
                ])
                ->action(function (array $data): void {
                    $this->record->update([
                        'featured_in_carousel' => true,
                        'discount_percentage' => $data['discount_percentage'],
                        'promotion_start' => $data['promotion_start'] ?? null,
                        'promotion_end' => $data['promotion_end'] ?? null,
                    ]);
                }),

            Action::make('removePromo')
                ->label('Remover do carousel')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn () => (bool) $this->record->featured_in_carousel)
                ->action(function (): void {
                    $this->record->update([
                        'featured_in_carousel' => false,
                    ]);
                }),

            DeleteAction::make(),
        ];
    }
}
