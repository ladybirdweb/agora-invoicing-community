<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\TaxRequest;
use App\Model\Common\Country;
use App\Model\Common\State;
use App\Model\Payment\Tax;
use App\Model\Payment\TaxClass;
use App\Model\Payment\TaxOption;
use App\Model\Product\ProductName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Form;
use Lang;


class TaxController extends Controller
{
    public $tax;
    public $country;
    public $state;
    public $tax_option;
    public $tax_class;

    public function __construct()
    {
        $this->middleware('auth', ['except' => 'GetState']);
        // $this->middleware('admin', ['except' => 'GetState']);

        $tax = new Tax();
        $this->tax = $tax;

        $country = new Country();
        $this->country = $country;

        $state = new State();
        $this->state = $state;

       
        
        $tax_option = new TaxOption();
        $this->tax_option = $tax_option;

        $tax_class = new TaxClass();
        $this->tax_class = $tax_class;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        try {
            $options = $this->tax_option->find(1);
            if (!$options) {
                $options = '';
            }
           // $taxes= Tax::all();
            // $taxes =Tax::where('id', $id)->first();
          $taxes =Tax::all();
          $products=ProductName::all();
        
            $states = \DB::table('states_subdivisions')->where('country_code_char2','IN')->pluck('state_subdivision_name','state_subdivision_id')->toArray();
            return view('themes.default1.payment.tax.index', compact('taxes', 'products','states'));
        } catch (\Exception $ex) {
            dd($ex);
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function GetTax()
    
    {
        $new_tax = Tax::select('id', 'product_name_id','tax_class_name','rate','start_date','end_date','time_zone')->get();
        // try

        return\ DataTables::of($new_tax)
       
                        ->addColumn('tax_name', function ($model) {

                           
                            return ucfirst($model->tax_class_name);
                            
                       })

                         ->addColumn('product_name', function ($model) {

                    
                            return ucfirst($model->product_name);
                            //  if ($this->product_name_id->where('id', $model->product_name)->first()) {
                            // return $this->product_name->where('id', $model->product_name_id)->first()->product_name;
                        // }
                        // else {
                        //         return 'Not available';
                        //     }

                            
                       })

                       //    ->addColumn('country', function ($model) {

                           
                       //      return ucfirst($model->country);
                            
                       // })

                       //     ->addColumn('state', function ($model) {

                           
                       //      return ucfirst($model->state);
                            
                       // })
                            ->addColumn('rate', function ($model) {

                           
                            return ucfirst($model->rate);
                            
                       })
                             ->addColumn('startdate', function ($model) {

                           
                            return ucfirst($model->start_date);
                            
                       })
                              ->addColumn('enddate', function ($model) {

                           
                            return ucfirst($model->end_date);
                            
                       })
                               ->addColumn('timezone', function ($model) {

                           
                            return ucfirst($model->time_zone);
                            
                       })
                         ->addColumn('action', function ($model) {
                         // 
                           
                              // return '<a href='.('#edit-category-option/'.$model->id)." class=' btn btn-sm btn-primary ' .data-toggle='modal' .data-target='#edit-category-option'>Edit</a>";
                              
                              return '<a href='.('#edit-tax-option/'.$model->id).' class=" btn btn-sm btn-primary " data-toggle="modal" data-target="#edit-tax-option" data-name="'.$model->tax_class_name.'"  data-id="'.$model->id.'" data-tax_rate="'.$model->rate.'" data-state="'.$model->state.'" >Edit</a>';


                            








                              

                               })


                        ->rawColumns(['product_name', 'tax_class_name', 'rate', 'start_date', 'end_date','time_zone','action'])

                           
                       

                      
                        ->make(true);


      
    }

     public function options(Request $request)
    {
     // dd('ok');
        $new_tax = new Tax();
        $new_tax->tax_class_name = Input::get('name');
         $new_tax->product_name_id = Input::get('product');
         $new_tax->country =Input::get('country');
          $new_tax->state =Input::get('state');
           $new_tax->rate = Input::get('rate');
            $new_tax->start_date = Input::get('sdate');
            $new_tax->end_date = Input::get('edate');
            $new_tax->time_zone = Input::get('timezone');
            $new_tax->save();

               return back();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(TaxRequest $request)
    {
        try {
            $this->tax->fill($request->input())->save();

            return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $tax = $this->tax->where('id', $id)->first();
            $classes = $this->tax_class->lists('name', 'id')->toArray();
            $state = \App\Http\Controllers\Front\CartController::getStateByCode($tax->state);
            $states = \App\Http\Controllers\Front\CartController::findStateByRegionId($tax->country);

            if (count($classes) == 0) {
                $classes = $this->tax_class->get();
            }

            return view('themes.default1.payment.tax.edit', compact('tax', 'classes', 'states', 'state'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
       
        
          $taxes=Tax::find($id);

        $taxes->tax_class_name=$request->input('taxname');
        $taxes->product_name=$request->input('product');
        $taxes->country=$request->input('country');
        $taxes->state=$request->input('state');
        $taxes->rate=$request->input('rate');
        $taxes->start_date=$request->input('sdate');
        $taxes->end_date=$request->input('edate');
        $taxes->time_zone=$request->input('timezone');
        $taxes->save();
         return redirect ('/tax')->
    with('response','Category updated Successfully');    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy(Request $request)
    {
        try {
            $ids = $request->input('select');
            if (!empty($ids)) {
                foreach ($ids as $id) {
                    $tax = $this->tax->where('id', $id)->first();
                    if ($tax) {
                        $tax->delete();
                    } else {
                        echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>".\Lang::get('message.alert').'!</b> '.\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        '.\Lang::get('message.no-record').'
                </div>';
                        //echo \Lang::get('message.no-record') . '  [id=>' . $id . ']';
                    }
                }
                echo "<div class='alert alert-success alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>".\Lang::get('message.alert').'!</b> '.\Lang::get('message.success').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        '.\Lang::get('message.deleted-successfully').'
                </div>';
            } else {
                echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>".\Lang::get('message.alert').'!</b> '.\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        '.\Lang::get('message.select-a-row').'
                </div>';
                //echo \Lang::get('message.select-a-row');
            }
        } catch (\Exception $e) {
            echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>".\Lang::get('message.alert').'!</b> '.\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        '.$e->getMessage().'
                </div>';
        }
    }

    public function GetState(Request $request, $state)
    {
        try {
           
            $id= $state;
            $states = \App\Model\Common\State::where('country_code_char2', $id)->get();
            // return $states;
            echo '<option value=>Select State</option>';
            foreach ($states as $state) {
                echo '<option value='.$state->state_subdivision_code.'>'.$state->state_subdivision_name.'</option>';
            }
        } catch (\Exception $ex) {
            echo "<option value=''>Problem while loading</option>";
             return redirect()->back()->with('fails', $ex->getMessage());
        }

    }
    


    // public function options(Request $request)
    // {
    //     try {
    //         //dd($request->all());
    //         $method = $request->method();
    //         if ($method == 'PATCH') {
    //             $rules = $this->tax_option->find(1);
    //             if (!$rules) {
    //                 $this->tax_option->create($request->input());
    //             } else {
    //                 $rules->fill($request->input())->save();
    //             }
    //         } else {
    //             $v = \Validator::make($request->all(), ['name' => 'required']);
    //             if ($v->fails()) {
    //                 return redirect()->back()
    //                     ->withErrors($v)
    //                     ->withInput();
    //             }
    //             $this->tax_class->fill($request->input())->save();
    //         }

    //         return redirect()->back()->with('success', \Lang::get('message.created-successfully'));
    //     } catch (\Exception $ex) {
    //         return redirect()->back()->with('fails', $ex->getMessage());
    //     }
    // }
}
