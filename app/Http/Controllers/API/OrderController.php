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
        $products = Product::select('name','price')->get();


        return $this->sendResponse($products->toArray(), 'Products retrieved successfully.');
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


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $ordered_query = "SELECT products.name, products.price, orderitems.product_id, orderitems.order_id FROM products,orderitems
        WHERE orderitems.product_id = products.id AND orderitems.order_id = 18";
        $ordered_products = DB::select($ordered_query);
        $orders_query;

        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }


        return $this->sendResponse($product->toArray(), 'Product retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $input = $request->all();


        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required'
        ]);


        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }


        $product->name = $input['name'];
        $product->detail = $input['detail'];
        $product->save();


        return $this->sendResponse($product->toArray(), 'Product updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();


        return $this->sendResponse($product->toArray(), 'Product deleted successfully.');
    }
}