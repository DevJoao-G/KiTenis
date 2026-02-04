<?php

namespace App\Filament\Resources\Orders;

use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\Orders\Schemas\OrderForm;
use App\Filament\Resources\Orders\Tables\OrdersTable;
use App\Models\Order;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    // ✅ Filament v4 (na sua versão): string|UnitEnum|null
    protected static string|UnitEnum|null $navigationGroup = 'Operações';

    protected static ?string $recordTitleAttribute = 'id';

    public static function getNavigationLabel(): string
    {
        return 'Vendas';
    }

    public static function getModelLabel(): string
    {
        return 'Venda';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Vendas';
    }

    public static function form(Schema $schema): Schema
    {
        return OrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrdersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'edit'  => EditOrder::route('/{record}/edit'),
        ];
    }
}
