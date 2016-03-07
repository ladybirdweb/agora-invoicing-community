<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Front\FrontendPage;

class PageController extends Controller {

    public $page;

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('admin');

        $page = new FrontendPage();
        $this->page = $page;
    }

    public function index() {
        try {
            return view('themes.default1.front.page.index');
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function GetPages() {
        return \Datatable::collection($this->page->get())
                        ->addColumn('#', function($model) {
                            return "<input type='checkbox' value=" . $model->id . " name=select[] id=check>";
                        })
                        ->showColumns('name', 'url', 'created_at')
                        ->addColumn('content', function($model) {
                            return str_limit($model->content, 10, '...');
                        })
                        ->addColumn('action', function($model) {
                            return "<a href=" . url('pages/' . $model->id . '/edit') . " class='btn btn-sm btn-primary'>Edit</a>";
                        })
                        ->searchColumns('name', 'content')
                        ->orderColumns('name')
                        ->make();
    }

    public function create() {
        try {

            $parents = $this->page->lists('name', 'id')->toArray();
            return view('themes.default1.front.page.create', compact('parents'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function edit($id) {
        try {
            $page = $this->page->where('id',$id)->first();
            $parents = $this->page->where('id', '!=', $id)->lists('name', 'id')->toArray();
            return view('themes.default1.front.page.edit', compact('parents','page'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function store(Request $request) {
        try {
            
            $this->page->fill($request->input())->save();
            return redirect()->back()->with('success',\Lang::get('message.saved-successfully'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function update($id, Request $request) {
        try {
            $page = $this->page->where('id',$id)->first();
            $page->fill($request->input())->save();
            return redirect()->back()->with('success',\Lang::get('message.updated-successfully'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    Public function GetPageUrl($slug) {
        $productController = new \App\Http\Controllers\Product\ProductController();
        $url = $productController->GetMyUrl();
        $segment = $this->Addsegment(['public']);
        $url = $url.$segment;
        
        $slug = str_slug($slug, '-');
        echo $url.'/'.$slug;
    }

    public function GetSlug($slug) {
        $slug = str_slug($slug, '-');
        echo $slug;
    }
    
    public function Addsegment($segments=[]){
        $segment = '';
        foreach($segments as $seg){
            $segment.='/'.$seg;
        }
        return $segment;
        
    }


    public function Generate(Request $request){
       // dd($request->all());
        if($request->has('slug')){
            $slug = $request->input('slug');
            return $this->GetSlug($slug);
        }
        if($request->has('url')){
            $slug = $request->input('url');
            return $this->GetPageUrl($slug);
            
        }
    }
    
    public function show($slug){
        try{
            $page = $this->page->where('slug',$slug)->where('publish',1)->first();
            return view('themes.default1.front.page.show', compact('page'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request) {
        
        try {
            $ids = $request->input('select');
            if (!empty($ids)) {
                foreach ($ids as $id) {
                    $page= $this->page->where('id', $id)->first();
                    if ($page) {
                       // dd($page);
                        $page->delete();
                    } else {
                        echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>" . \Lang::get('message.alert') . "!</b> " . \Lang::get('message.failed') . "
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        " . \Lang::get('message.no-record') . "
                </div>";
                        //echo \Lang::get('message.no-record') . '  [id=>' . $id . ']';
                    }
                }
                echo "<div class='alert alert-success alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>" . \Lang::get('message.alert') . "!</b> " . \Lang::get('message.success') . "
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        " . \Lang::get('message.deleted-successfully') . "
                </div>";
            } else {
                echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>" . \Lang::get('message.alert') . "!</b> " . \Lang::get('message.failed') . "
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        " . \Lang::get('message.select-a-row') . "
                </div>";
                //echo \Lang::get('message.select-a-row');
            }
        } catch (\Exception $e) {
            echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>" . \Lang::get('message.alert') . "!</b> " . \Lang::get('message.failed') . "
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        " . $e->getMessage() . "
                </div>";
        }
    }
    
    public function Search(Request $request){
        try{
            $search =  $request->input('q');
            $model = $this->Result($search,  $this->page);
            
            return view('themes.default1.front.page.search',compact('model'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
        
    }
    
    public function Result($search,$model){
        try{
            $model = $model->where('name','like','%'.$search.'%')->orWhere('content','like','%'.$search.'%')->paginate(10);
            return $model->setPath('search');
        } catch (\Exception $ex) {
            //dd($ex);
            throw new \Exception('Can not get the search result');
        }
    }
        

}
