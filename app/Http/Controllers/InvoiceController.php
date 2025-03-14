<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function get_all_invoices(){

        $invoices = Invoice::with('customer')->orderBy('id','DESC')->get();
        return response()->json([
            'invoices' => $invoices
        ],200);

    }
    public function search_invoices(Request $request){

        $search = $request->get('s');
        if($search != null){

            $invoices = Invoice::with('customer')->where('id','LIKE',"%$search%")->get();
            return response()->json([
                'invoices' => $invoices
            ],200);
        }
        else{
            return
                 $this->get_all_invoices();

        }
    }
    public function create_invoice(Request $request){

        $counter = Counter::where('key','invoice')->first();
        $random = Counter::where('key','invoice')->first();

        $invoice = Invoice::orderBy('id','DESC')->first();

        if($invoice){

         $invoice = $invoice->id+1;
         $counters = $counter->value + $invoice;



        }
        else{
            $counters =$counter->value;
        }

        $formData = [
            'number' =>$counter->prefix.$counters,
            'customer_id' =>null,
            'customet'=>null,
            'date'=>date('Y-m-d'),
            'due-date'=>null,
            'reference' =>null,
            'discount'=>0,
            'terms_and_Conditions'=>'Default',
            'items'=>[
                'product_id'=>null,
                'product'=>null,
                'unit_price'=>0,
                'quantity'=>1
            ]
            ];

          return  response()->json($formData);


    }

    public function add_invoice(Request $request){

      $invoiceitem = $request->input('invoice_item');

      $invoicedata['sub_total'] = $request->input('sub_total');
      $invoicedata['total'] = $request->input('total');
      $invoicedata['customer_id'] = $request->input('customer_id');
      $invoicedata['number'] = $request->input('number');
      $invoicedata['date'] = $request->input('date');
      $invoicedata['due_date'] = $request->input('due_date');
      $invoicedata['discount'] = $request->input('discount');
      $invoicedata['reference'] = $request->input('reference');
      $invoicedata['terms_and_conditions'] = $request->input('terms_and_conditions');

      $invoice = Invoice::create($invoicedata);

      foreach(json_decode($invoiceitem) as $item){
        $itemdata['product_id'] = $item->id;
        $itemdata['invoice_id'] = $invoice->id;
        $itemdata['quantity'] = $item->quantity;
        $itemdata['unit_price'] = $item->unit_price;

        InvoiceItem::create($itemdata);



      }

    }

    public function show_invoice($id){

    $invoice = Invoice::with('customer','invoice_item.product')->find($id);
    return response()->json([
        'invoice' => $invoice
    ],200);
    }

    public function edit_invoice($id){

        $invoice = Invoice::with('customer','invoice_item.product')->find($id);
        return response()->json([
            'invoice' => $invoice
        ],200);
        }

        public function delete_invoice_items($id){

               $invoiceitem = InvoiceItem::findOrFail($id);
               $invoiceitem->delete();

        }

        public function update_invoice_items(Request $request,$id){

               $invoicess = Invoice::where('id',$id)->first();

               $invoiceitem = $request->input('invoice_item');
               $invoicess->invoice_item()->delete();

               $invoicedata['sub_total'] = $request->input('sub_total');
               $invoicedata['total'] = $request->input('total');
               $invoicedata['customer_id'] = $request->input('customer_id');
               $invoicedata['number'] = $request->input('number');
               $invoicedata['date'] = $request->input('date');
               $invoicedata['due_date'] = $request->input('due_date');
               $invoicedata['discount'] = $request->input('discount');
               $invoicedata['reference'] = $request->input('reference');
               $invoicedata['terms_and_conditions'] = $request->input('terms_and_conditions');


               $invoicess->update($invoicedata);



               foreach(json_decode($invoiceitem) as $item){
                $itemdata['product_id'] = $item->product_id;
                $itemdata['invoice_id'] = $invoicess->id;
                $itemdata['quantity'] = $item->quantity;
                $itemdata['unit_price'] = $item->unit_price;

                InvoiceItem::create($itemdata);


               }






        }


       public function delete_invoice($id){


        $invoice = Invoice::findOrFail($id);
        $invoice ->invoice_item()->delete();
        $invoice->delete();


       }

}
