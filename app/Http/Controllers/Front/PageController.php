<?php

namespace App\Http\Controllers\Front;

use App\DefaultPage;
use App\Http\Controllers\Common\TemplateController;
use App\Http\Controllers\Controller;
use App\Model\Common\PricingTemplate;
use App\Model\Front\FrontendPage;
use App\Model\Product\Product;
use App\Model\Product\ProductGroup;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public $page;

    public function __construct()
    {
        $this->middleware('auth', ['except'=>['pageTemplates', 'contactUs']]);

        $page = new FrontendPage();
        $this->page = $page;
    }

    public function index()
    {
        try {
            return view('themes.default1.front.page.index');
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getPages()
    {
        return \DataTables::of($this->page->get())
                        ->addColumn('checkbox', function ($model) {
                            return "<input type='checkbox' class='page_checkbox' 
                            value=".$model->id.' name=select[] id=check>';
                        })
                        ->addColumn('name', function ($model) {
                            return ucfirst($model->name);
                        })
                        ->addColumn('url', function ($model) {
                            return $model->url;
                        })
                        ->addColumn('created_at', function ($model) {
                            return getDateHtml($model->created_at);
                        })

                        ->addColumn('action', function ($model) {
                            return '<a href='.url('pages/'.$model->id.'/edit')
                            ." class='btn btn-sm btn-secondary btn-xs'".tooltip('Edit')."<i class='fa fa-edit'
                                 style='color:white;'> </i></a>";
                        })

                          ->rawColumns(['checkbox', 'name', 'url',  'created_at', 'action'])
                        ->make(true);
        // ->searchColumns('name', 'content')
                        // ->orderColumns('name')
                        // ->make();
    }

    public function create()
    {
        try {
            $parents = $this->page->pluck('name', 'id')->toArray();

            return view('themes.default1.front.page.create', compact('parents'));
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $page = $this->page->where('id', $id)->first();
            $parents = $this->page->where('id', '!=', $id)->pluck('name', 'id')->toArray();
            $selectedDefault = DefaultPage::value('page_id');
            $date = $this->page->where('id', $id)->pluck('created_at')->first();
            $publishingDate = date('d/m/Y', strtotime($date));
            $selectedParent = $this->page->where('id', $id)->pluck('parent_page_id')->toArray();
            $parentName = $this->page->where('id', $selectedParent)->pluck('name', 'id')->toArray();

            return view('themes.default1.front.page.edit', compact('parents', 'page', 'selectedDefault', 'publishingDate','selectedParent',
                'parentName'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'    => 'required',
            'publish' => 'required',
            'slug'    => 'required',
            'url'     => 'required',
            'content' => 'required',
        ]);

        try {
            $url = $request->input('url');
            if ($request->input('type') == 'contactus') {
                $url = url('/contact-us');
            }
            $this->page->name = $request->input('name');
            $this->page->publish = $request->input('publish');
            $this->page->slug = $request->input('slug');
            $this->page->url = $url;
            $this->page->parent_page_id = $request->input('parent_page_id');
            $this->page->type = $request->input('type');
            $this->page->content = $request->input('content');
            $this->page->save();

            return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name'           => 'required',
            'publish'        => 'required',
            'slug'           => 'required',
            'url'            => 'required',
            'content'        => 'required',
            'created_at'     => 'required',
        ]);

        try {
            if ($request->input('default_page_id') != '') {
                $page = $this->page->where('id', $id)->first();
                $page->fill($request->except('created_at'))->save();
                $date = \DateTime::createFromFormat('d/m/Y', $request->input('created_at'));
                $page->created_at = $date->format('Y-m-d H:i:s');
                $page->save();
                $defaultUrl = $this->page->where('id', $request->input('default_page_id'))->pluck('url')->first();
                DefaultPage::find(1)->update(['page_id'=>$request->input('default_page_id'), 'page_url'=>$defaultUrl]);
            } else {
                DefaultPage::find(1)->update(['page_id'=>1, 'page_url'=>url('my-invoices')]);
            }

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getPageUrl($slug)
    {
        $productController = new \App\Http\Controllers\Product\ProductController();
        //  $url = url('/');
        //  $segment = $this->addSegment(['public/pages']);
        $url = url('/');

        $slug = str_slug($slug, '-');
        echo $url.'/pages'.'/'.$slug;
    }

    public function getSlug($slug)
    {
        $slug = str_slug($slug, '-');
        echo $slug;
    }

    public function addSegment($segments = [])
    {
        $segment = '';
        foreach ($segments as $seg) {
            $segment .= '/'.$seg;
        }

        return $segment;
    }

    public function generate(Request $request)
    {
        // dd($request->all());
        if ($request->has('slug')) {
            $slug = $request->input('slug');

            return $this->getSlug($slug);
        }
        if ($request->has('url')) {
            $slug = $request->input('url');

            return $this->getPageUrl($slug);
        }
    }

    public function show($slug)
    {
        try {
            $page = $this->page->where('slug', $slug)->where('publish', 1)->first();
            if ($page && $page->type == 'cart') {
                return $this->cart();
            }

            return view('themes.default1.front.page.show', compact('page'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Response
     */
    public function destroy(Request $request)
    {
        try {
            $ids = $request->input('select');
            $defaultPageId = DefaultPage::pluck('page_id')->first();
            if (! empty($ids)) {
                foreach ($ids as $id) {
                    if ($id != $defaultPageId) {
                        $page = $this->page->where('id', $id)->first();
                        if ($page) {
                            $page->delete();
                        } else {
                            echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */
                    \Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.no-record').'
                </div>';
                            //echo \Lang::get('message.no-record') . '  [id=>' . $id . ']';
                        }
                        echo "<div class='alert alert-success alert-dismissable'>
                    <i class='fa fa-ban'></i>

                    <b>"./* @scrutinizer ignore-type */ \Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */
                    \Lang::get('message.success').'

                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.deleted-successfully').'
                </div>';
                    } else {
                        echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */ \Lang::get('message.can-not-delete-default-page').'
                </div>';
                    }
                }
            } else {
                echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.select-a-row').'
                </div>';
                //echo \Lang::get('message.select-a-row');
            }
        } catch (\Exception $e) {
            echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        '.$e->getMessage().'
                </div>';
        }
    }

    public function transform($type, $data, $trasform = [])
    {
        $config = \Config::get("transform.$type");
        $result = '';
        $array = [];
        foreach ($trasform as $trans) {
            $array[] = $this->checkConfigKey($config, $trans);
        }
        $c = count($array);
        for ($i = 0; $i < $c; $i++) {
            $array1 = $this->keyArray($array[$i]);
            $array2 = $this->valueArray($array[$i]);
            $result .= str_replace($array1, $array2, $data);
        }

        return $result;
    }

    /**
     * Get Page Template when Group in Store Dropdown is
     * selected on the basis of Group id.
     *
     * @author Ashutosh Pathak <ashutosh.pathak@ladybirdweb.com>
     *
     * @date   2019-01-10T01:20:52+0530
     *
     * @param int $groupid    Group id
     * @param int $templateid Id of the Template
     *
     * @return longtext The Template to be displayed
     */
    public function pageTemplates(int $templateid, int $groupid)
    {
        try {
            $data = PricingTemplate::findorFail($templateid)->data;
            $headline = ProductGroup::findorFail($groupid)->headline;
            $tagline = ProductGroup::findorFail($groupid)->tagline;
            $productsRelatedToGroup = ProductGroup::find($groupid)->product()->where('hidden', '!=', 1)
            ->orderBy('created_at', 'desc')->get(); //Get ALL the Products Related to the Group
            $trasform = [];
            $templates = $this->getTemplateOne($productsRelatedToGroup, $data, $trasform);

            return view('themes.default1.common.template.shoppingcart', compact('templates', 'headline', 'tagline'));
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function contactUs()
    {
        try {
            return view('themes.default1.front.contact');
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Get  Template For Products.
     * @param $helpdesk_products
     * @param $data
     * @param $trasform
     * @return string
     */
    public function getTemplateOne($helpdesk_products, $data, $trasform)
    {
        try {
            $template = '';
            $temp_controller = new TemplateController();
            if (count($helpdesk_products) > 0) {
                foreach ($helpdesk_products as $product) {
                    //Store all the values in $trasform variable for shortcodes to read from
                    $trasform[$product['id']]['price'] = $temp_controller->leastAmount($product['id']);
                    $trasform[$product['id']]['price-description'] = self::getPriceDescription($product['id']);
                    $trasform[$product['id']]['name'] = $product['name'];
                    $trasform[$product['id']]['feature'] = $product['description'];
                    $trasform[$product['id']]['subscription'] = $temp_controller
                    ->plans($product['shoping_cart_link'], $product['id']);
                    $trasform[$product['id']]['url'] = "<input type='submit' 
                    value='Order Now' class='btn btn-dark btn-modern btn-outline py-2 px-4'></form>";
                }
                $template = $this->transform('cart', $data, $trasform);
            }

            return $template;
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Get Price Description(eg: Per Year,Per Month ,One-Time) for a Product.
     *
     * @author Ashutosh Pathak <ashutosh.pathak@ladybirdweb.com>
     *
     * @date   2019-01-09T00:20:09+0530
     *
     * @param int $productid Id of the Product
     *
     * @return string $priceDescription        The Description of the Price
     */
    public function getPriceDescription(int $productid)
    {
        try {
            $plan = Product::find($productid)->plan();
            $description = $plan ? $plan->planPrice->first() : '';
            $priceDescription = $description ? $description->price_description : '';

            return  $priceDescription;
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function checkConfigKey($config, $transform)
    {
        $result = [];
        if ($config) {
            foreach ($config as $key => $value) {
                if (array_key_exists($key, $transform)) {
                    $result[$value] = $transform[$key];
                }
            }
        }

        return $result;
    }

    public function keyArray($array)
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[] = $key;
        }

        return $result;
    }

    public function valueArray($array)
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[] = $value;
        }

        return $result;
    }

    public function postContactUs(Request $request)
    {
        $this->validate($request, [
            'name'    => 'required',
            'email'   => 'required|email',
            'message' => 'required',
        ]);

        $set = new \App\Model\Common\Setting();
        $set = $set->findOrFail(1);

        try {
            $from = $set->email;
            $fromname = $set->company;
            $toname = '';
            $to = $set->company_email;
            $data = '';
            $data .= 'Name: '.strip_tags($request->input('name')).'<br/>';
            $data .= 'Email: '.strip_tags($request->input('email')).'<br/>';
            $data .= 'Message: '.strip_tags($request->input('message')).'<br/>';
            $data .= 'Mobile: '.strip_tags($request->input('country_code').$request->input('Mobile')).'<br/>';
            $subject = 'Faveo billing enquiry';
            if (emailSendingStatus()) {
                $mail = new \App\Http\Controllers\Common\PhpMailController();
                $mail->sendEmail($from, $to, $data, $subject);
            }

            //$this->templateController->Mailing($from, $to, $data, $subject);
            return redirect()->back()->with('success', 'Your message was sent successfully. Thanks.');
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }
}
