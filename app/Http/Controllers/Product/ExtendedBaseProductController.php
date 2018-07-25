<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Model\Payment\TaxProductRelation;
use App\Model\Product\Product;
use App\Model\Product\ProductUpload;
use Illuminate\Http\Request;

class ExtendedBaseProductController extends Controller
{
    public function getUpload($id)
    {
        $new_upload = ProductUpload::where('product_id', '=', $id)
        ->select('id', 'product_id', 'title', 'description', 'version', 'file')
        ->get();

        return \DataTables::of($new_upload)
    ->addColumn('checkbox', function ($model) {
        return "<input type='checkbox' class='upload_checkbox' value=".$model->id.' name=select[] id=checks>';
    })

    ->addColumn('product_id', function ($model) {
        return ucfirst($this->product->where('id', $model->product_id)->first()->name);
    })

    ->addColumn('title', function ($model) {
        return ucfirst($model->title);
    })
    ->addColumn('description', function ($model) {
        return ucfirst($model->description);
    })
    ->addColumn('version', function ($model) {
        return $model->version;
    })

    ->addColumn('file', function ($model) {
        return $model->file;
    })
    ->addColumn('action', function ($model) {
        return '<a href='.('#edit-upload-option/'.$model->id).' 
         class=" btn btn-sm btn-primary " data-title="'.$model->title.'"
          data-description="'.$model->description.'" data-version="'
          .$model->version.'" data-id="'.$model->id.'" onclick="openEditPopup(this)" >Edit</a>';
    })
    ->rawcolumns(['checkbox', 'product_id', 'title', 'description', 'version', 'file', 'action'])
    ->make(true);
    }

    //Update the File Info
    public function uploadUpdate($id, Request $request)
    {
        $file_upload = ProductUpload::find($id);

        $file_upload->title = $request->input('title');
        $file_upload->description = $request->input('description');
        $file_upload->version = $request->input('version');
        if ($request->file) {
            $file = $request->file('file')->getClientOriginalName();

            $destination = storage_path().'/products';
            $request->file('file')->move($destination, $file);
            $file_upload->file = $file;
        }
        $file_upload->save();

        return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
    }

    public function saveTax($taxes, $product_id)
    {
        if ($taxes) {
            TaxProductRelation::where('product_id', $product_id)->delete();
            foreach ($taxes as $tax) {
                $newTax = new TaxProductRelation();
                $newTax->product_id = $product_id;
                $newTax->tax_class_id = $tax;
                $newTax->save();
            }
        }

        return $newTax;
    }

    public function getProductField($productid)
    {
        try {
            $field = '';
            $product = Product::find($productid);
            if ($product) {
                if ($product->require_domain == 1) {
                    $field .= "<div class='col-md-4 form-group'>
                        <label class='required'>"./* @scrutinizer ignore-type */
                         \Lang::get('message.domain')."</label>
                        <input type='text' name='domain' class='form-control' 
                        id='domain' placeholder='http://example.com'>
                </div>";
                }
            }

            return $field;
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            return $ex->getMessage();
        }
    }
}
