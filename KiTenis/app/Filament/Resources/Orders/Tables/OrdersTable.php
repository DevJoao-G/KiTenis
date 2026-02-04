<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('#')->sortable(),

                TextColumn::make('user.name')->label('Cliente')->searchable()->sortable()->default('—'),
                TextColumn::make('user.email')->label('E-mail')->searchable()->toggleable(isToggledHiddenByDefault: true)->default('—'),

                TextColumn::make('user_id')
                    ->label('ID do usuário')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('total')->label('Total')->money('BRL')->sortable(),
                TextColumn::make('status')->label('Status')->badge()->sortable()->searchable(),

                TextColumn::make('external_reference')->label('Referência externa')->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('preference_id')->label('ID da preferência')->searchable()->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('payment_method')
                    ->label('Método de pagamento')
                    ->formatStateUsing(fn ($state) => $state ? mb_strtoupper($state) : '—')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('paid_at')->label('Pago em')->dateTime('d/m/Y H:i')->sortable()->toggleable(),

                TextColumn::make('created_at')->label('Criado em')->dateTime('d/m/Y H:i')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->label('Atualizado em')->dateTime('d/m/Y H:i')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending'    => 'Pendente',
                        'processing' => 'Processando',
                        'shipped'    => 'Enviado',
                        'delivered'  => 'Entregue',
                        'cancelled'  => 'Cancelado',
                    ]),
            ])
            ->recordActions([
                EditAction::make()->label('Abrir'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Excluir selecionados'),
                ]),
            ]);
    }
}
