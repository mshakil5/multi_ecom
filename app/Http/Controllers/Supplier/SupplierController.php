<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Supplier;
use Illuminate\Support\Facades\Hash;
use App\Models\Purchase;
use App\Models\OrderDetails;
use App\Models\Order;
use App\Models\Transaction;

class SupplierController extends Controller
{
    public function dashboard()
    {
        return view('supplier.dashboard');
    }

    public function getSupplierProfile()
    {
        $supplierId = Auth::guard('supplier')->user()->id;
        $supplier = Supplier::findOrFail($supplierId);
        return view('supplier.profile', compact('supplier'));
    }

    public function updateSupplierProfile(Request $request)
    {
        $data = Supplier::find(Auth::guard('supplier')->user()->id);

        $request->validate([
            'id_number' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:suppliers,email,'.$data->id,
            'phone' => 'nullable|numeric',
            'password' => 'nullable|min:8',
            'confirm_password' => 'nullable|same:password',
            'vat_reg' => 'nullable|numeric',
            'contract_date' => 'nullable|date',
            'address' => 'nullable|string',
            'company' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
                $uploadedFile = $request->file('image');

                if ($data->image && file_exists(public_path('images/supplier/'. $data->image))) {
                    unlink(public_path('images/supplier/'. $data->image));
                }

                $randomName = mt_rand(10000000, 99999999). '.'. $uploadedFile->getClientOriginalExtension();
                $destinationPath = public_path('images/supplier/');
                $path = $uploadedFile->move($destinationPath, $randomName); 
                $data->image = $randomName;
                $data->save();
           }

            $data->id_number = $request->id_number;
            $data->name = $request->name;
            $data->email = $request->email;
            $data->phone = $request->phone;
            $data->vat_reg = $request->vat_reg;
            $data->address = $request->address;
            $data->company = $request->company;
            $data->contract_date = $request->contract_date;
            if(isset($request->password)){
                $data->password = Hash::make($request->password);
            }

        $data->save();

        return redirect()->back()->with('success', 'Updated successfully.');
    }

    public function productPurchaseHistory()
    {
        $supplierId = Auth::guard('supplier')->user()->id;
        $purchases = Purchase::with(['purchaseHistory.product', 'supplier'])
                        ->whereHas('supplier', function ($query) use ($supplierId) {
                            $query->where('id', $supplierId);
                        })
                        ->orderBy('id', 'DESC')
                        ->get();

        return view('supplier.purchase_history', compact('purchases'));
    }

    public function getPurchaseHistory(Purchase $purchase)
    {
        $purchase = Purchase::with(['supplier', 'purchaseHistory.product'])
            ->select([
                'id', 
                'purchase_date', 
                'invoice', 
                'supplier_id', 
                'purchase_type', 
                'ref', 
                'net_amount', 
                'paid_amount', 
                'due_amount'
            ])
            ->findOrFail($purchase->id);

        return response()->json($purchase);
    }

    public function getSupplierOrders()
    {
        $supplierId = Auth::guard('supplier')->user()->id;
        $supplier = Supplier::findOrFail($supplierId);
        $productIds = $supplier->supplierStocks()->pluck('product_id');
        $orderDetails = OrderDetails::whereIn('product_id', $productIds)->get();
        
        $orders = Order::whereHas('orderDetails', function ($query) use ($productIds) {
            $query->whereIn('product_id', $productIds);
        })->with(['orderDetails' => function ($query) use ($productIds) {
            $query->whereIn('product_id', $productIds);
        }])->get();
        return view('supplier.orders', compact('orders'));
    }

    public function showOrderDetails($orderId)
    {
        $supplierId = Auth::guard('supplier')->user()->id;
        $orderDetails = OrderDetails::where('order_id', $orderId)
            ->where('supplier_id', $supplierId)
            ->with(['product', 'order.user'])
            ->get();
        $order = $orderDetails->first()->order;

        return view('supplier.order_details', compact('order', 'orderDetails'));
    }

    public function supplierTransaction()
    {
        $supplierId = Auth::guard('supplier')->user()->id;
        $transactions = Transaction::where('supplier_id', $supplierId)
                                ->orderBy('id', 'desc')
                                ->select('id', 'amount', 'date', 'note')
                                ->get();
        return view('supplier.transaction', compact('transactions'));
    }

}
