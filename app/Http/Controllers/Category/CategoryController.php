<?php

namespace App\Http\Controllers\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Category\Category;
use Form;
use Lang;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         try{
            $categories= Category::all();
        return view('themes.default1.product.category.index',compact('categories'));
        }
        catch (\Exception $e)
        {
            return redirect('/')->with('fails',$e->getMessage());
        }    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


      public function getCategory()

        {
        $new_category = Category::select('id', 'name')->get();
        // try

        return\ DataTables::of($new_category)
            ->addColumn('Name', function ($model) {
               return ucfirst($model->name);
            })
            ->addColumn('action', function ($model) {
                         // 
                           
                              // return '<a href='.('#edit-category-option/'.$model->id)." class=' btn btn-sm btn-primary ' .data-toggle='modal' .data-target='#edit-category-option'>Edit</a>";
                              
                              // return '<a href='.('#edit-category-option/'.$model->id).' class=" btn btn-sm btn-primary " data-toggle="modal" data-target="#edit-category-option">Edit</a>';
                             return "<a href=#edit-category-option class='btn btn-primary' data-toggle='modal' data-target=#edit-category-option".$model->id.'>'.\Lang::get('message.edit')."</a>



                               <div class='modal fade' id=edit-category-option".$model->id.">
<div class='modal-dialog'>
    <div class='modal-content'>
        <div class='modal-header'>
            <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
            <h4 class='modal-title'>Edit Type</h4>
        </div>
        ".Form::model($model, ['url' => 'category/'.$model->id, 'method' => 'patch'])."
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


                        ->rawColumns(['name','action'])

                           
                       

                      
                        ->make(true);


      
    }


     public function options(Request $request)
    {
        $new_category = new Category();
        $new_category->name = $request->name;
        $new_category->save();
        // Category::create($request->all());
        return back();
    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            
            'name'            => 'required',
            
        ]);
          $category=Category::find($id);

        $category->name=$request->input('name');
        $category->save();
         return redirect ('/categories')->
    with('response','Category updated Successfully');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
