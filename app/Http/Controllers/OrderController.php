<?php

namespace App\Http\Controllers;

use App\Models\Fruit;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->paginate(1000);
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $products = Fruit::where('quantity', '>', 0)->get();
        return view('orders.create', compact('products'));
    }

    public function show(Order $order)
    {
        $order->load('items.fruit', 'items.secondFruit');
        return view('orders.show', compact('order'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'products' => 'required|array',
            'products.*.juice_type' => 'required|in:fresh,mix,berry',
            'products.*.id' => 'required|exists:fruits,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.sugar_level' => 'required|string',
            'products.*.ice_level' => 'required|string',
            'products.*.second_fruit_id' => 'nullable|exists:fruits,id',
        ]);

        DB::beginTransaction();
        try {
            $totalPrice = 0;

            foreach ($request->products as $product) {
                $fruit = Fruit::findOrFail($product['id']);

                // Check stock for the first fruit
                if ($fruit->quantity < 2) {
                    throw new \Exception("Not enough stock for {$fruit->name}");
                }

                // Check stock for the second fruit if necessary
                if (in_array($product['juice_type'], ['mix', 'berry']) && !empty($product['second_fruit_id'])) {
                    $secondFruit = Fruit::findOrFail($product['second_fruit_id']);
                    if ($secondFruit->quantity < 2) {
                        throw new \Exception("Not enough stock for {$secondFruit->name}");
                    }
                }

                // Price calculation
                $itemPrice = ($product['juice_type'] === 'fresh') ? 18000 * $product['quantity'] : 20000 * $product['quantity'];
                $totalPrice += $itemPrice;
            }

            $order = Order::create([
                'customer_name' => $request->customer_name,
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);

            // Add order items
            foreach ($request->products as $product) {
                $fruit = Fruit::findOrFail($product['id']);
                $secondFruitId = null;

                // Handle second fruit for mix/berry juices
                if (in_array($product['juice_type'], ['mix', 'berry']) && !empty($product['second_fruit_id'])) {
                    $secondFruit = Fruit::findOrFail($product['second_fruit_id']);
                    $secondFruitId = $secondFruit->id;
                }

                $itemPrice = ($product['juice_type'] === 'fresh') ? 18000 * $product['quantity'] : 20000 * $product['quantity'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_name' => $product['juice_type'] . ": {$fruit->name}",
                    'quantity' => $product['quantity'],
                    'sugar_level' => $product['sugar_level'],
                    'ice_level' => $product['ice_level'],
                    'price' => $itemPrice,
                    'juice_type' => $product['juice_type'],
                    'fruit_id' => $fruit->id,
                    'second_fruit_id' => $secondFruitId,
                    'stock_reduced' => false,
                ]);
            }

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in creating order: ' . $e->getMessage()); // Log error for debugging
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        Log::info("Changing order status from {$oldStatus} to {$newStatus}"); // Debug log

        DB::beginTransaction();
        try {
            if ($oldStatus === 'completed' && $newStatus !== 'completed') {
                return redirect()->back()->withErrors(['error' => 'Cannot change status from completed to another status.']);
            }

            $order->status = $newStatus;
            $order->save();

            // Process completed status: Reduce stock
            if ($newStatus === 'completed') {
                foreach ($order->items as $item) {
                    Log::info("Reducing stock for product: {$item->product_name}");

                    // Reduce stock for main fruit
                    $fruit = Fruit::find($item->fruit_id);
                    if ($fruit) {
                        if ($fruit->quantity >= 2 * $item->quantity) {
                            $fruit->quantity -= 2 * $item->quantity;
                            $fruit->save();
                        } else {
                            throw new \Exception("Not enough stock for {$item->product_name}");
                        }
                    }

                    // Reduce stock for second fruit if applicable
                    if ($item->second_fruit_id) {
                        $secondFruit = Fruit::find($item->second_fruit_id);
                        if ($secondFruit) {
                            if ($secondFruit->quantity >= 2 * $item->quantity) {
                                $secondFruit->quantity -= 2 * $item->quantity;
                                $secondFruit->save();
                            } else {
                                throw new \Exception("Not enough stock for second fruit in {$item->product_name}");
                            }
                        }
                    }

                    // Mark stock reduction as true
                    $item->stock_reduced = true;
                    $item->save();
                }
            }

            // Process cancelled status: Restore stock
            if ($oldStatus === 'completed' && $newStatus === 'cancelled') {
                foreach ($order->items as $item) {
                    Log::info("Restoring stock for product: {$item->product_name}");

                    if ($item->stock_reduced) {
                        // Restore stock for main fruit
                        $fruit = Fruit::find($item->fruit_id);
                        if ($fruit) {
                            $fruit->quantity += 2 * $item->quantity;
                            $fruit->save();
                        }

                        // Restore stock for second fruit
                        if ($item->second_fruit_id) {
                            $secondFruit = Fruit::find($item->second_fruit_id);
                            if ($secondFruit) {
                                $secondFruit->quantity += 2 * $item->quantity;
                                $secondFruit->save();
                            }
                        }

                        // Mark stock reduction as false
                        $item->stock_reduced = false;
                        $item->save();
                    }
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Order status updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in updating status: ' . $e->getMessage()); // Log error for debugging
            return redirect()->back()->with('error', 'Failed to update order status: ' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
}
