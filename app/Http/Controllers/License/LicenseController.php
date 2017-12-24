<?php

namespace App\Http\Controllers\License;

use App\Http\Controllers\Controller;
use App\Model\License\License;
use Form;
use Illuminate\Http\Request;
use Lang;

class LicenseController extends Controller
{
   
    public function index()
    {
        try {
            $licenses = License::all();

            return view('themes.default1.product.license.index', compact('licenses'));
        } catch (\Exception $e) {
            return redirect('/')->with('fails', $e->getMessage());
        }
    }

    public function getLicense()
    {
        $new_license = License::select('id', 'name')->get();
        // try

        return\ DataTables::of($new_license)

                        ->addColumn('name', function ($model) {
                            return ucfirst($model->name);
                        })
                         ->addColumn('Action', function ($model) {
                             //

                             // return '<a href='.('#edit-category-option/'.$model->id)." class=' btn btn-sm btn-primary ' .data-toggle='modal' .data-target='#edit-category-option'>Edit</a>";

                             return "<a href=#edit-license-option class='btn btn-primary' data-toggle='modal' data-target=#edit-license-option".$model->id.'>'.\Lang::get('message.edit')."</a>

                             
                                 

                                 <div class='modal fade' id=edit-license-option".$model->id.">
<div class='modal-dialog'>
    <div class='modal-content'>
        <div class='modal-header'>
            <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
            <h4 class='modal-title'>Edit Currency</h4>
        </div>
        ".Form::model($model, ['url' => 'license/'.$model->id, 'method' => 'patch'])."
        <div class='modal-body'>
           
            
                
            

            <div class='form-group'>
               
                ".Form::label('name', Lang::get('message.name'), ['class' => 'required']).'
                                    '.Form::text('name', null, ['class' => 'form-control'])."

            </div>
            

        </div>
        <div class='modal-footer'>
            <button type=button id=close class='btn btn-default pull-left' data-dismiss=modal>Close</button>
            <input type=submit class='btn btn-primary' value=".\Lang::get('message.save').'>
                
        </div>
       
             '.Form::close().'
        
        
        
    </div>
   
</div>
</div>';

 




                               })
   

                        ->rawColumns(['name','Action'])

                           
                       

             ->rawColumns(['name', 'Action'])

   ->make(true);
    }

    public function options(Request $request)
    {
        $new_license = new License();
        $new_license->name = $request->name;
        $new_license->save();
        // Category::create($request->all());
        return back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [

            'name'            => 'required',

        ]);
        $license = License::find($id);

        $license->name = $request->input('name');
        $license->save();

        return redirect('/license')->
    with('response', 'Category updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
