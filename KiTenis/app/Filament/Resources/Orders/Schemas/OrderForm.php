<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // ✅ CARD 1: Resumo da venda
            Section::make('Resumo da venda')
                ->description('Dados do cliente, pagamento e referências.')
                ->schema([
                    Grid::make([
                        'default' => 1,
                        'md' => 6,
                    ])->schema([
                        Placeholder::make('pedido_id')
                            ->label('Venda')
                            ->content(fn ($record) => $record?->id ? ('#' . $record->id) : '—')
                            ->columnSpan(['md' => 1]),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending'    => 'Pendente',
                                'processing' => 'Processando',
                                'shipped'    => 'Enviado',
                                'delivered'  => 'Entregue',
                                'cancelled'  => 'Cancelado',
                            ])
                            ->required()
                            ->columnSpan(['md' => 2]),

                        Placeholder::make('total')
                            ->label('Total')
                            ->content(fn ($record) => 'R$ ' . number_format((float) ($record?->total ?? 0), 2, ',', '.'))
                            ->columnSpan(['md' => 3]),

                        Placeholder::make('cliente_nome')
                            ->label('Cliente')
                            ->content(fn ($record) => $record?->user?->name ?? '—')
                            ->columnSpan(['md' => 3]),

                        Placeholder::make('cliente_email')
                            ->label('E-mail')
                            ->content(fn ($record) => $record?->user?->email ?? '—')
                            ->columnSpan(['md' => 3]),

                        Placeholder::make('payment_method')
                            ->label('Método')
                            ->content(fn ($record) => $record?->payment_method ? mb_strtoupper($record->payment_method) : '—')
                            ->columnSpan(['md' => 2]),

                        Placeholder::make('paid_at')
                            ->label('Pago em')
                            ->content(fn ($record) => $record?->paid_at?->format('d/m/Y H:i') ?? '—')
                            ->columnSpan(['md' => 2]),

                        Placeholder::make('preference_id')
                            ->label('ID da preferência')
                            ->content(fn ($record) => $record?->preference_id ?? '—')
                            ->columnSpan(['md' => 3]),

                        Placeholder::make('external_reference')
                            ->label('Referência externa')
                            ->content(fn ($record) => $record?->external_reference ?? '—')
                            ->columnSpan(['md' => 3]),
                    ]),
                ]),

            // ✅ CARD 2: Itens comprados (cada item em stack)
            Section::make('Itens comprados')
                ->description('Produtos desta venda.')
                ->schema([
                    Repeater::make('items')
                        ->label('')
                        ->relationship('items')
                        ->columnSpanFull()
                        ->itemLabel(fn ($record) => $record?->product?->name
                            ? ($record->quantity . 'x ' . $record->product->name)
                            : 'Item'
                        )
                        ->schema([
                            Grid::make([
                                'default' => 1, // ✅ tudo um embaixo do outro
                            ])->schema([
                                Placeholder::make('produto')
                                    ->label('Produto')
                                    ->content(fn ($record) => $record?->product?->name ?? 'Produto removido'),

                                Placeholder::make('variacao')
                                    ->label('Variação')
                                    ->content(function ($record) {
                                        $size = $record?->size ? ('Tam ' . $record->size) : null;
                                        $color = $record?->color ?: null;

                                        $v = trim(($size ?? '') . ' ' . ($color ?? ''));
                                        return $v !== '' ? $v : '—';
                                    }),

                                Placeholder::make('quantidade')
                                    ->label('Quantidade')
                                    ->content(fn ($record) => (string) ((int) ($record?->quantity ?? 0))),

                                Placeholder::make('preco')
                                    ->label('Preço unitário')
                                    ->content(fn ($record) => 'R$ ' . number_format((float) ($record?->price ?? 0), 2, ',', '.')),

                                Placeholder::make('subtotal')
                                    ->label('Subtotal')
                                    ->content(function ($record) {
                                        $qty = (int) ($record?->quantity ?? 0);
                                        $price = (float) ($record?->price ?? 0);
                                        return 'R$ ' . number_format($qty * $price, 2, ',', '.');
                                    }),
                            ]),
                        ])
                        ->addable(false)
                        ->deletable(false)
                        ->reorderable(false),
                ]),
        ]);
    }
}
