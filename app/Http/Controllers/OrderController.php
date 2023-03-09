<?php

namespace App\Http\Controllers;

use App\Models\CustomCake;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Image;
use Notification;
use App\Notifications\EmailNotification;

class OrderController extends Controller
{
    public function index(Request $request) {
        if($request->status === "onCart") {
            $orders = Order::with('user', 'product')->where('status', $request->status)->where('total_price', 0)->orderByDesc('id')->get();
        } else if (isset($request->status)) {
            $orders = Order::with('user', 'product')->where('status', $request->status)->orderByDesc('id')->get();
        } else {
            $orders = Order::with('user', 'product')->orderByDesc('id')->get();
        }

        return response()->json([
            "data" => $orders
        ]);
    }

    public function store(Request $request) {

        $image = "";
        if($request->hasFile('image')) {
            $image = $request->image->store('custom-cakes');
            $img = Image::make(public_path('storage/' . $image))->fit(400, 500);
            $img->save();
        }

        $totalPrice = empty($request->product_id) 
                    ? 0
                    : $this->getTotalPrice($request->product_id, $request->quantity);

        $order = Order::create([
            "user_id" => Auth::id(),
            "product_id" => $request->product_id,
            "quantity" => $request->quantity,
            "total_price" => $totalPrice,
            "status" => $request->status,
            "message" => $request->message,
            "type" => $request->type,
            "image" => $image,
            "remarks" => $request->remarks,
            "delivery_date" => $request->delivery_date,
            "delivery_address" => $request->delivery_address,
        ]);

        return response()->json([
            "message" => "Added to Cart",
            "data" => $order,
        ]);
    }

    public function show($id) {
        $data = Order::where('id', $id)->with('user', 'product')->first();

        return response()->json([
            'data' => $data
        ]);
    }

    public function getUserCart(Request $request) {
        $id = Auth::user()->id;
        $status = $request->status;

        $orders = Order::where('user_id', $id)->orderByDesc('id')->where('status', $status)->with('product')->get();

        return response()->json([
            "message" => "Fetch All Cart Success",
            "data" => $orders,
            "status" => $status
        ]);
    }

    public function delete($id) {
        $order = Order::where('id', $id)->first();

        $order->delete();

        return response()->json([
            'message' => 'Order has been deleted.',
        ]);
    }

    public function update(Request $request, $id) {
        $order = Order::where('id', $id)->first();

        if($order->status == "onCart" && $order->total_price == 0) {
            $order->update([
                "unit_price" => $request->unit_price,
                "total_price" => $request->unit_price * $order->quantity
            ]);
        }
        else { 
            $order->update($request->all());
        } 

        $this->sendMail($order, $request->status);

        return response()->json([
            'message' => 'Order has been updated.',
            'data' => $order
        ]);
    }

    public function updateAddToCart(Request $request, $id) {
        $order = Order::where('product_id', $id)->with('product')->first();
        $qty = $order->quantity + $request->quantity;
        $totalPrice = $qty * $order->product->price;

        $order->update([
            'quantity' => $qty,
            'total_price' => $totalPrice
        ]);

        return response()->json([
            'message' => "Product Added to Cart",
            'data' => $order
        ]);
    }

    public function getTotalPrice($id, $qty) {
        $product = Product::where('id', $id)->first();
        $totalPrice = $product->price * $qty;
        return $totalPrice;
    }

    public function getTotalOrder($orders) {
        $total = 0;
        foreach($orders as $item) {
            $total += $item->total_price;
        }

        return $total;
    }

    public function getTotalOfAllItems(Request $request) {
        $id = Auth::user()->id;
        $total = 0;

        $orders = Order::where('user_id', $id)->where('status', $request->status)->with('product')->get();

        foreach($orders as $item) {
            $total += $item->total_price;
        }

        $customCakes = CustomCake::where('user_id', $id)->where('status', $request->status)->get();

        foreach($customCakes as $item) {
            $total += $item->price * $item->quantity;
        }

        return response()->json([
            'message' => "Total Price of all items in the cart",
            'totalPrice' => $total,
        ]);
    }

    public function getQtyEachOrder(Request $request) {
        $onCart = Order::where('status', "onCart")->where('total_price', 0)->count();
        $toPay = Order::where('status', "Paid")->count();
        $processing = Order::where('status', "Processing")->count();
        $delivery = Order::where('status', "Ready-For-Delivery")->count();
        $completed = Order::where('status', "Completed")->count();

        return response()->json([
            'data' => [
                'oncart' => $onCart,
                'topay' => $toPay,
                'processing' => $processing,
                'delivery' => $delivery,
                'completed' => $completed,
            ]
        ]);
    }

    public function getQtyEachUserOrder(Request $request) {
        $id = Auth::user()->id;

        $oncart = Order::where('user_id', $id)->orderByDesc('id')->where('status', "onCart")->count();
        $paid = Order::where('user_id', $id)->orderByDesc('id')->where('status', "Paid")->count();
        $process = Order::where('user_id', $id)->orderByDesc('id')->where('status', "Processing")->count();
        $deliver = Order::where('user_id', $id)->orderByDesc('id')->where('status', "Ready-For-Delivery")->count();
        $completed = Order::where('user_id', $id)->orderByDesc('id')->where('status', "Completed")->count();

        return response()->json([
            "oncart" => $oncart,
            "paid" => $paid,
            "process" => $process,
            "deliver" => $deliver,
            "completed" => $completed,
        ]);
    }

    public function sendMail($order, $status) {
        $user = User::where("id", $order->user_id)->first();

        $details = [
            'greeting' => 'Hi ' . $user['first_name'],
            'details' => 'Heres the updated details of your order: ' ,
            'order' => "Order ID: " . $order->id,
            'date' => "Delivery Date: " . $order->delivery_date,
            'address' => "Delivery Address: " . $order->delivery_address,
            'status' => "Status: " . $order->status,
            'thanks' => 'Thank you for your patience',
            'actionText' => 'Website',
            'actionURL' => url('https://purplebox.com'),
        ];
  
        Notification::send($user, new EmailNotification($details));
    }
}
