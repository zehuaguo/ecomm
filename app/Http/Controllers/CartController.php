<?php

namespace App\Http\Controllers;

use Gloudemans\Shoppingcart\Facades\Cart;
use App\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
       $mightAlsoLike = Product::mightAlsoLike()->get();

        return view('cart')->with('mightAlsoLike',$mightAlsoLike);
    }

      /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
      public function create()
      {
        
      }

      
      

      public function store(Request $request)
      {
        $duplicates = Cart::search(function ($cartItem, $rowId) use ($request) {
            return $cartItem->id === $request->id;
        });
        if ($duplicates->isNotEmpty()) {
            return redirect()->route('cart.index')->with('success_message', 'Item is already in your cart!');
        }


        Cart::add($request->id, $request->name, 1, $request->price)
        ->associate('App\Product');
        

        return redirect()->route('cart.index')->with('success_message','Item was added to your cart!');
        
      }

      public function show($id)
      {



      }
      public function edit($id)
      {



      }
      public function update(Request $request, $id)
      {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|numeric|between:1,5'
        ]);


        if ($validator->fails()) {
            session()->flash('errors', collect(['Quantity must be between 1 and 5.']));
            return response()->json(['success' => false], 500);
        }
       // if ($request->quantity > $request->productQuantity) {
       //     session()->flash('errors', collect(['We currently do not have enough items in stock.']));
       //     return response()->json(['success' => false], 400);
       // }




        Cart::update($id,$request->quantity);
        session()->flash('success_message', 'Quantity was updated successfully!');
        return response()->json(['success' => true]);



      }


       public function destroy($id)
    {

        Cart::remove($id);
        return back()->with('success_message', 'Item has been removed!');
    }

    public function switchToSaveForLater($id)
    {
        $item = Cart::get($id);
        Cart::remove($id);
        
        Cart::instance('saveForLater')->add($item->id, $item->name, 1, $item->price)
            ->associate('App\Product');
        return redirect()->route('cart.index')->with('success_message', 'Item has been Saved For Later!');
    }


}