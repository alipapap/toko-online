<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Store;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pesanan', Order::count())
                ->description('Jumlah semua pesanan')
                ->descriptionIcon('heroicon-o-shopping-cart')
                ->color('info'),

            Stat::make('Total Pendapatan', 'Rp ' . number_format(Payment::sum('amount'), 0, ',', '.'))
                ->description('Total dari semua pembayaran')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Total Produk', Product::count())
                ->description('Produk aktif')
                ->descriptionIcon('heroicon-o-shopping-bag')
                ->color('warning'),

            Stat::make('Total Toko', Store::count())
                ->description('Toko terdaftar')
                ->descriptionIcon('heroicon-o-building-storefront')
                ->color('gray'),
        ];
    }
}