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
        $orders = Orders::select('name', 'price')->get();
        return $this->sendResponse($products->toArray(), 'Orders retrieved successfully.');
    }

    /**
     * Store a newly created orders in storage.
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

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $customer_id = Auth::user()->id;
        $data = array(
            'customer_id' => $customer_id
        );

        $order = Order::create($data);
        $order_id = $order->id;
        $product_ids = explode(",", $items['product_id']);
        foreach ($product_ids as $id) {
            $product_id = $items['product_id'];
            $orderitem = new Orderitem();
            $orderitem->order_id = $order_id;
            $orderitem->product_id = $id;
            $orderitem->save();
        }
        $ordered_products = DB::select("SELECT  products.name, products.price,  orderitems.order_id FROM products,orderitems
        WHERE orderitems.product_id = products.id AND orderitems.order_id = $order_id");

        $total = DB::select("SELECT SUM(price) AS Total FROM products,orderitems
         WHERE orderitems.product_id = products.id AND orderitems.order_id = $order_id");

        $order_details = array_merge($ordered_products, $total);

        return $this->sendResponse($order_details, 'Order was created successfully.');
    }


}