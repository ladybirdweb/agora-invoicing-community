<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\License\LicensePermissionsController;
use App\Model\Common\Setting;
use App\Model\Payment\Plan;
use App\Model\Product\Product;
use App\Model\Product\ProductUpload;
use Bugsnag;
use Illuminate\Http\Request;

class BaseProductController extends ExtendedBaseProductController
{
    public function getMyUrl()
    {
        $server = new Request();
        $url = $_SERVER['REQUEST_URI'];
        $server = parse_url($url);
        $server['path'] = dirname($server['path']);
        $server = parse_url($server['path']);
        $server['path'] = dirname($server['path']);
        $server = 'http://'.$_SERVER['HTTP_HOST'].$server['path'];

        return $server;
    }

    /*
    * Get Product Qty if Product can be modified
     */
    public function getProductQtyCheck($productid, $planid)
    {
        try {
            $check = self::checkMultiProduct($productid);
            if ($check == true) {
                $value = Product::find($productid)->planRelation->find($planid)->planPrice->first()->product_quantity;
                $value = $value == null ? 1 : $value;

                return "<div class='col-md-4 form-group'>
	                        <label class='required'>"./* @scrutinizer ignore-type */
                            \Lang::get('message.quantity')."</label>
	                        <input type='text' name='quantity' class='form-control' id='quantity' value='$value'>
	                </div>";
            }
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            return $ex->getMessage();
        }
    }

    /*
    * Check whether Product is allowed for Increasing the Quantity fromAdmin Panel
    * @param int $productid
    *
    * @return boolean
     */
    public function checkMultiProduct(int $productid)
    {
        $product = new Product();
        $product = $product->find($productid);
        if ($product) {
            if ($product->can_modify_quantity == 1) {
                return true;
            }
        }

        return false;
    }

    public function getAgentQtyCheck($productid, $planid)
    {
        try {
            $check = self::checkMultiAgent($productid);
            if ($check == true) {
                $value = Product::find($productid)->planRelation->find($planid)->planPrice->first()->no_of_agents;
                $value = $value == null ? 0 : $value;

                return "<div class='col-md-4 form-group'>
                            <label class='required'>"./* @scrutinizer ignore-type */
                            \Lang::get('message.agent')."</label>
                            <input type='text' name='agents' class='form-control' id='agents' value='$value'>
                    </div>";
            }
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            return $ex->getMessage();
        }
    }

    /*
    * Check whether No of the GAents can be modified or not fromAdmin Panel
    * @param int $productid
    *
    * @return boolean
     */
    public function checkMultiAgent(int $productid)
    {
        $product = new Product();
        $product = $product->find($productid);
        if ($product) {
            if ($product->can_modify_agent == 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the Subscription and Price Based on the Product Selected while generating Invoice (Admin Panel).
     *
     * @param int     $productid
     * @param Request $request
     *
     * @return [type]
     */
    public function getSubscriptionCheck(int $productid, Request $request)
    {
        try {
            $controller = new \App\Http\Controllers\Front\CartController();
            $field = '';
            $price = '';

            $plan = new Plan();
            $plans = $plan->where('product', $productid)->pluck('name', 'id')->toArray();
            if (count($plans) > 0) {//If Plan Exist For A product, Display Dropdown for Plans
                $field = "<div class='col-md-4 form-group'>
                        <label class='required'>"./* @scrutinizer ignore-type */
                        \Lang::get('message.subscription').'</label>
                       '.\Form::select(
                            'plan',
                            ['' => 'Select', 'Plans' => $plans],
                            null,
                            ['class' => 'form-control required', 'id' => 'plan', 'onchange' => 'getPrice(this.value)']
                        ).'
                </div>';
            } else {//If No Plan Exist For A Product
                $userid = $request->input('user');
                $price = $controller->cost($productid, $userid);
            }
            $result = ['price' => $price, 'field' => $field];

            return response()->json($result);
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());
            Bugsnag::notifyException($ex);

            return $ex->getMessage();
        }
    }

    public function userDownload($uploadid, $userid, $invoice_number, $version_id = '')
    {
        try {
            if (\Auth::user()->role != 'admin') {
                if (\Auth::user()->id != $userid) {
                    throw new \Exception('This user has no permission for this action');
                }
            }
            $user = new \App\User();
            $user = $user->findOrFail($userid);

            $invoice = new \App\Model\Order\Invoice();
            $invoice = $invoice->where('number', $invoice_number)->first();

            if ($user && $invoice) {
                if ($user->active == 1) {
                    $order = $invoice->order()->orderBy('id', 'desc')->select('product')->first();
                    $product_id = $order->product;
                    $name = Product::where('id', $product_id)->value('name');
                    $invoice_id = $invoice->id;
                    $release = $this->downloadProduct($uploadid, $userid, $invoice_id, $version_id);
                    if (is_array($release) && array_key_exists('type', $release)) {
                        $release = $release['release'];

                        return view('themes.default1.front.download', compact('release'));
                    } else {
                        header('Content-type: Zip');
                        header('Content-Description: File Transfer');
                        header('Content-Disposition: attachment; filename='.$name.'.zip');
                        header('Content-type: application/zip');
                        header('Content-Length: '.filesize($release));
                        readfile($release);
                        // ob_end_clean();
                        // flush();
                    }
                } else {
                    return redirect('auth/login')->with('fails', \Lang::get('activate-your-account'));
                }
            } else {
                return redirect('auth/login')->with('fails', \Lang::get('please-purcahse-a-product'));
            }
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());
            Bugsnag::notifyException($ex);

            return redirect('auth/login')->with('fails', $ex->getMessage());
        }
    }

    public function getRelease($owner, $repository, $order_id, $file)
    {
        if ($owner && $repository) {//If the Product is downloaded from Github
            $github_controller = new \App\Http\Controllers\Github\GithubController();
            $relese = $github_controller->listRepositories($owner, $repository, $order_id);

            return ['release'=>$relese, 'type'=>'github'];
        } elseif ($file) {
            //If the Product is Downloaded from FileSystem
            $fileName = $file->file;
            $path = Setting::find(1)->value('file_storage');
            // $relese = storage_path().'/products'.'//'.$fileName; //For Local Server
            //$relese = '/home/faveo/products/'.$file->file;
            $relese = $path.'/'.$file->file;

            return $relese;
        }
    }

    public function getReleaseAdmin($owner, $repository, $file)
    {
        if ($owner && $repository) {
            $github_controller = new \App\Http\Controllers\Github\GithubController();
            $relese = $github_controller->listRepositoriesAdmin($owner, $repository);

            return ['release'=>$relese, 'type'=>'github'];
        } elseif ($file->file) {
            // $relese = storage_path().'\products'.'\\'.$file->file;
            //    $relese = '/home/faveo/products/'.$file->file;
            $path = Setting::find(1)->value('file_storage');
            $relese = $path.'/'.$file->file;

            return $relese;
        }
    }

    public function downloadProductAdmin($id)
    {
        try {
            $product = Product::findOrFail($id);
            $type = $product->type;
            $owner = $product->github_owner;
            $repository = $product->github_repository;
            $file = ProductUpload::where('product_id', '=', $id)->select('file')
            ->orderBy('created_at', 'desc')
            ->first();
            $permissions = LicensePermissionsController::getPermissionsForProduct($id);
            if ($permissions['downloadPermission'] == 1) {
                $relese = $this->getReleaseAdmin($owner, $repository, $file);

                return $relese;
            }
        } catch (\Exception $e) {
            Bugsnag::notifyException($e);

            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    /**
     * Get Price For a Particular Plan Selected.
     *
     * get productid,userid,plan id as request
     *
     * @return json The final Price of the Prduct
     */
    public function getPrice(Request $request)
    {
        try {
            $id = $request->input('product');
            $userid = $request->input('user');
            $plan = $request->input('plan');
            $controller = new \App\Http\Controllers\Front\CartController();
            $price = $controller->cost($id, $userid, $plan);
            $field = $this->getProductField($id);
            $quantity = $this->getProductQtyCheck($id, $plan);
            $agents = $this->getAgentQtyCheck($id, $plan);
            $result = ['price' => $price, 'field' => $field, 'quantity'=>$quantity, 'agents'=>$agents];

            return response()->json($result);
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);
            $result = ['price' => $ex->getMessage(), 'field' => ''];

            return response()->json($result);
        }
    }

    public function updateVersionFromGithub($productid)
    {
        try {
            if (\Input::has('github_owner') && \Input::has('github_repository')) {
                $owner = \Input::get('github_owner');
                $repo = \Input::get('github_repository');
                $product = Product::find($productid);
                $github_controller = new \App\Http\Controllers\Github\GithubController();
                $version = $github_controller->findVersion($owner, $repo);
                $product->version = $version;
                $product->save();
            }
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Check Whether No. of Agents Allowed or Product Qunatity on cart.
     *
     * @author Ashutosh Pathak <ashutosh.pathak@ladybirdweb.com>
     *
     * @date   2019-01-11T00:18:49+0530
     *
     * @param int $productid
     *
     * @return bool
     */
    public function allowQuantityOrAgent(int $productid)
    {
        $product = Product::find($productid);
        $allowAgents = $product->show_agent == 1 ? true : false;

        return $allowAgents;
    }

    /**
     * Checks Permission for Incresing the no. of Agents/Quantity in Cart.
     *
     *
     * @param int $productid The id of the Product added to the cart
     *
     * @return array The permissons for Agents and Quantity
     */
    public function isAllowedtoEdit(int $productid)
    {
        $product = Product::where('id', $productid)->first();

        $agentModifyPermission = $product->can_modify_agent;
        $quantityModifyPermission = $product->can_modify_quantity;

        return ['agent'=>$agentModifyPermission, 'quantity'=>$quantityModifyPermission];
    }
}
