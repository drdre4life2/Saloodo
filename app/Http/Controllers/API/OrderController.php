<?php


namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Product;
use App\Order;
use App\Orderitem;
use Validator;
use DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Orders::select('name','price')->get();
        return $this->sendResponse($products->toArray(), 'Orders retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $items = $request->all();
        $validator = Validator::make($items, [
            'product_id' => 'required'
        ]);

        $customer_id = Auth::user()->id;

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $data = array(
            'customer_id' => $customer_id,
            'order_status' => 'ordered'
        );

        $order = Order::create($data);
        $order_id = $order->id;
        $order_data = array(
            'order_id' => $order_id
        );

        $product_id = $items['product_id'];
        $orderitem = new Orderitem();
        $orderitem->order_id = $order_id;
        $orderitem->product_id = $items['product_id'];
        $orderitem->save();
        $ordered_products = DB::select("SELECT name, sum(price) as total FROM products WHERE id = '$product_id'");
        
        return $this->sendResponse($ordered_products, 'Order was created successfully.');
    }

   
}