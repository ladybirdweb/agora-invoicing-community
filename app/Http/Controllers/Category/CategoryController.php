<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Model\Category\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // public $category;

    //  public function __construct()
    // {
    //     $category = new Category();
    //     $this->category = $category;
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $categories = Category::all();

            return view('themes.default1.product.category.index', compact('categories'));
        } catch (\Exception $e) {
            return redirect('/')->with('fails', $e->getMessage());
        }
    }

    public function getCategory()
    {
        $new_category = Category::select('id', 'name')->get();
        // try

        return\ DataTables::of($new_category)

                        ->addColumn('name', function ($model) {
                            return ucfirst($model->name);
                        })
                         ->addColumn('Action', function ($model) {
                             //

                             // return '<a href='.('#edit-category-option/'.$model->id)." class=' btn btn-sm btn-primary ' .data-toggle='modal' .data-target='#edit-category-option'>Edit</a>";

                             return '<a href='.('#edit-category-option/'.$model->id).' class=" btn btn-sm btn-primary " data-toggle="modal" data-target="#edit-category-option">Edit</a>';
                         })

                        ->rawColumns(['name', 'Action'])

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //  try {

        //              $category = $this->category->pluck('name', 'id')->toArray();

        //              return view('themes.default1.product.category.edit-category-option', compact('category'));
        // } catch (\Exception $e) {
        //     return redirect()->back()->with('fails', $e->getMessage());
        // }
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
        //
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
