<?php
namespace App\Http\Controllers;

use App\Models\Fruit;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalFruits = Fruit::sum('quantity');
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        
        // Only count completed orders for revenue
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');
        
        $recentOrders = Order::orderBy('created_at', 'desc')->take(5)->get();
        $lowStockItems = Fruit::where('quantity', '<=', 10)->orderBy('quantity', 'asc')->take(5)->get();
        
        return view('dashboard.index', compact(
            'totalFruits',
            'totalOrders',
            'pendingOrders',
            'totalRevenue',
            'recentOrders',
            'lowStockItems'
        ));
    }
}