<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Model\Common\StatusSetting;
use App\Model\Payment\TaxProductRelation;
use App\Model\Product\Product;
use App\Model\Product\ProductUpload;
use Bugsnag;
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
        $autoUpdateStatus = StatusSetting::pluck('update_settings')->first();
        if ($autoUpdateStatus == 1) { //If License Setting Status is on,Add Product to the License Manager
            $productSku = $file_upload->product->product_sku;
            $updateClassObj = new \App\Http\Controllers\AutoUpdate\AutoUpdateController();
            $addProductToAutoUpdate = $updateClassObj->editVersion($request->input('version'), $productSku);
        }
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
    }

    /**
     * Whether the Product Requires the domain to be entered.
     *
     * @param int $productid
     */
    public function getProductField(int $productid)
    {
        try {
            $field = '';
            $product = Product::find($productid);
            if ($product->require_domain == 1) {
                $field .= "<div class='col-md-4 form-group'>
                        <label class='required'>"./* @scrutinizer ignore-type */
                         \Lang::get('message.domain')."</label>
                        <input type='text' name='domain' class='form-control' 
                        id='domain' placeholder='http://example.com'>
                </div>";
            }

            return $field;
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            return $ex->getMessage();
        }
    }

    public function adminDownload($id, $invoice = '', $api = false)
    {
        try {
            // $release = $this->getLinkToDownload($role, $invoice, $id);
            $release = $this->downloadProductAdmin($id);

            if (is_array($release) && array_key_exists('type', $release)) {
                header('Location: '.$release['release']);
                exit;
            } else {
                header('Content-type: Zip');
                header('Content-Description: File Transfer');
                header('Content-Disposition: attachment; filename=Faveo.zip');
                header('Content-Length: '.filesize($release));
                ob_end_clean();
                readfile($release);
            }
        } catch (\Exception $e) {
            if ($api) {
                return response()->json(['error'=>$e->getMessage()]);
            }
            Bugsnag::notifyException($e);

            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    /**
     * Save Values Related to Cart(eg: whether show Agents or Quantity in Cart etc).
     *
     * @author Ashutosh Pathak <ashutosh.pathak@ladybirdweb.com>
     *
     * @date   2019-01-07T14:34:54+0530
     *
     * @param Illuminate\Http\Request $input               All the Product Detais Sent from  the form
     * @param bool                    $can_modify_agent    Whether Agents can be modified by customer
     * @param bool                    $can_modify_quantity Whether Product Quantity can be modified by Customers
     *
     * @return
     */
    public function saveCartValues($input, bool $can_modify_agent, bool $can_modify_quantity)
    {
        $this->product->show_agent = $input['show_agent'] == 1 ? 1 : 0; //if Show Agents Selected
        $this->product->can_modify_agent = $can_modify_agent;
        $this->product->can_modify_quantity = $can_modify_quantity;
        $this->product->save();
    }

    /**
     * Save Values Related to Cart while Updating Produc(eg: whether show Agents or Quantityof Product in Cart etc).
     *
     * @author Ashutosh Pathak <ashutosh.pathak@ladybirdweb.com>
     *
     * @date   2019-01-07T20:40:20+0530
     *
     *@param  Illuminate\Http\Request     $input      All the Product Detais Sent from  the form
     * @param Illuminate\Http\Request; $request
     * @param array                    $product instance of the Product
     *
     * @return Save The Details
     */
    public function saveCartDetailsWhileUpdating($input, $request, $product)
    {
        $product->show_agent = $input['show_agent'] == 1 ? 1 : 0; //if Show Agents Selected
        if ($product->show_agent == 1) {
            $product->can_modify_quantity = 0;
            if ($request->has('can_modify_agent')) {
                $product->can_modify_agent = 1;
            } else {
                $product->can_modify_agent = 0;
                $product->can_modify_quantity = 0;
            }
        } else {
            $product->can_modify_agent = 0;
            if ($request->has('can_modify_quantity')) {
                $product->can_modify_quantity = 1;
            } else {
                $product->can_modify_agent = 0;
                $product->can_modify_quantity = 0;
            }
        }

        $product->save();
    }
}
