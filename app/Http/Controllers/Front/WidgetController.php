<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Model\Front\Widgets;
use Illuminate\Http\Request;

class WidgetController extends Controller
{
    public $widget;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');

        $widget = new Widgets();
        $this->widget = $widget;
    }

    public function index()
    {
        try {
            return view('themes.default1.front.widgets.index');
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getPages()
    {
        return \DataTables::of($this->widget->get())
                       ->addColumn('checkbox', function ($model) {
                           return "<input type='checkbox' class='widget_checkbox' 
                            value=".$model->id.' name=select[] id=check>';
                       })
                          ->addColumn('name', function ($model) {
                              return ucfirst($model->name);
                          })
                            ->addColumn('type', function ($model) {
                                return $model->type;
                            })
                              ->addColumn('created_at', function ($model) {
                                  return $model->created_at;
                              })
                        // ->showColumns('name', 'type', 'created_at')
                        ->addColumn('content', function ($model) {
                            return str_limit($model->content, 10, '...');
                        })
                        ->addColumn('action', function ($model) {
                            return '<a href='.url('widgets/'.$model->id.'/edit')."
                             class='btn btn-sm btn-primary btn-xs'><i class='fa fa-edit'
                                 style='color:white;'> </i>&nbsp;&nbsp;Edit</a>";
                        })
                        ->rawColumns(['checkbox', 'name', 'type', 'created_at', 'content', 'action'])
                        ->make(true);
        // ->searchColumns('name', 'content')
                        // ->orderColumns('name')
                        // ->make();
    }

    public function create()
    {
        try {
            return view('themes.default1.front.widgets.create');
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $widget = $this->widget->where('id', $id)->first();
            //dd($widget);
            return view('themes.default1.front.widgets.edit', compact('widget'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'    => 'required',
            'publish' => 'required',
            // 'content' => 'required',
            'type'    => 'required|unique:widgets',
        ]);

        try {
            $this->widget->fill($request->input())->save();

            return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name'    => 'required',
            'publish' => 'required',
            // 'content' => 'required',
            'type'    => 'required',
        ]);

        try {
            $widget = $this->widget->where('id', $id)->first();
            $widget->fill($request->input())->save();

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
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
            if (!empty($ids)) {
                foreach ($ids as $id) {
                    $widget = $this->widget->where('id', $id)->first();
                    if ($widget) {
                        // dd($page);
                        $widget->delete();
                    } else {
                        echo "<div class='alert alert-danger alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */\Lang::get('message.failed').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.no-record').'
                </div>';
                        //echo \Lang::get('message.no-record') . '  [id=>' . $id . ']';
                    }
                }
                echo "<div class='alert alert-success alert-dismissable'>
                    <i class='fa fa-ban'></i>
                    <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                    /* @scrutinizer ignore-type */ \Lang::get('message.success').'
                    <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                        './* @scrutinizer ignore-type */\Lang::get('message.deleted-successfully').'
                </div>';
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
}
