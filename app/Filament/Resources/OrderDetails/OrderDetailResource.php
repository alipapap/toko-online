<?php

namespace App\Filament\Resources\OrderDetails;

use App\Filament\Resources\OrderDetails\Pages\EditOrderDetail;
use App\Filament\Resources\OrderDetails\Pages\ListOrderDetails;
use App\Filament\Resources\OrderDetails\Pages\ViewOrderDetail;
use App\Filament\Resources\OrderDetails\Schemas\OrderDetailForm;
use App\Filament\Resources\OrderDetails\Schemas\OrderDetailInfolist;
use App\Filament\Resources\OrderDetails\Tables\OrderDetailsTable;
use App\Models\OrderDetail;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OrderDetailResource extends Resource
{
    protected static ?string $model = OrderDetail::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $recordTitleAttribute = 'orderdetail';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return OrderDetailForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OrderDetailInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrderDetailsTable::configure($table);
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
            'index' => ListOrderDetails::route('/'),
            'view' => ViewOrderDetail::route('/{record}'),
            'edit' => EditOrderDetail::route('/{record}/edit'),
        ];
    }
}