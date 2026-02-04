<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),

                IconColumn::make('featured_in_carousel')
                    ->label('Carrossel')
                    ->boolean()
                    ->toggleable(),

                IconColumn::make('is_promotion_active')
                    ->label('Promo ativa')
                    ->boolean()
                    ->toggleable(),

                TextColumn::make('discount_percentage')
                    ->label('Desc. %')
                    ->formatStateUsing(fn ($state) => filled($state)
                        ? rtrim(rtrim(number_format((float) $state, 2, '.', ''), '0'), '.') . '%'
                        : '-'
                    )
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('price')
                    ->label('Preço')
                    ->money('BRL')
                    ->sortable(),

                TextColumn::make('discounted_price')
                    ->label('Preço promo')
                    ->money('BRL')
                    ->toggleable(),

                TextColumn::make('stock')
                    ->label('Estoque')
                    ->numeric()
                    ->sortable(),

                ImageColumn::make('image')
                    ->label('Imagem')
                    ->disk('public'),

                TextColumn::make('category')
                    ->label('Categoria')
                    ->toggleable(),

                IconColumn::make('active')
                    ->label('Ativo')
                    ->boolean(),

                TextColumn::make('promotion_start')
                    ->label('Início')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('promotion_end')
                    ->label('Fim')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('promo')
                    ->label(fn ($record) => $record->featured_in_carousel ? 'Editar promoção' : 'Adicionar ao carrossel')
                    ->icon(fn ($record) => $record->featured_in_carousel ? 'heroicon-o-tag' : 'heroicon-o-plus-circle')
                    ->color(fn ($record) => $record->featured_in_carousel ? 'warning' : 'success')
                    ->modalHeading(fn ($record) => $record->featured_in_carousel ? 'Editar promoção' : 'Adicionar ao carrossel de promoções')
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
                    ->fillForm(fn ($record) => [
                        'discount_percentage' => $record->discount_percentage ?? 0,
                        'promotion_start' => $record->promotion_start,
                        'promotion_end' => $record->promotion_end,
                    ])
                    ->action(function (array $data, $record): void {
                        $record->update([
                            'featured_in_carousel' => true,
                            'discount_percentage' => $data['discount_percentage'],
                            'promotion_start' => $data['promotion_start'] ?? null,
                            'promotion_end' => $data['promotion_end'] ?? null,
                        ]);
                    }),

                Action::make('removePromo')
                    ->label('Remover do carrossel')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => (bool) $record->featured_in_carousel)
                    ->action(function ($record): void {
                        $record->update([
                            'featured_in_carousel' => false,
                        ]);
                    }),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
