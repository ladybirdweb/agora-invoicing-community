<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Common\Twitter\TwitterOAuth;
use App\Http\Controllers\Controller;
use App\Model\Common\SocialMedia;
use Exception;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    protected $social;

    public function __construct()
    {
        $this->middleware('auth', ['except' => 'getTweets']);

        $social = new SocialMedia();
        $this->social = $social;
    }

    public function index()
    {
        try {
            return view('themes.default1.common.social.index');
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getSocials()
    {
        try {
            $social = $this->social->get();

            return \Datatable::collection($social)
                            ->addColumn('#', function ($model) {
                                return "<input type='checkbox' value=".$model->id.' name=select[] id=check>';
                            })
                            ->showColumns('name', 'class', 'link')
                            ->addColumn('action', function ($model) {
                                return '<a href='.url('social-media/'.$model->id.'/edit')." class='btn btn-sm btn-primary'>Edit</a>";
                            })
                            ->searchColumns('name')
                            ->orderColumns('class')
                            ->make();
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function create()
    {
        try {
            return view('themes.default1.common.social.create');
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required',
            'link'     => 'required|url',
            'class'    => 'required',
            'fa_class' => 'required',
        ]);

        try {
            $this->social->fill($request->input())->save();

            return redirect()->back()->with('success', \Lang::get('message.saved-successfully'));
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $social = $this->social->findOrFail($id);

            return view('themes.default1.common.social.edit', compact('social'));
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name'     => 'required',
            'link'     => 'required|url',
            'class'    => 'required',
            'fa_class' => 'required',
        ]);

        try {
            $social = $this->social->findOrFail($id);
            $social->fill($request->input())->save();

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

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
                    $social = $this->social->where('id', $id)->first();
                    if ($social) {
                        $social->delete();
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

    public function getTweets()
    {
        try {
            $tweet_limit = 2;
            $username = 'faveohelpdesk';
            $consumer_key = '1BcWlYMQsiQqgBB4ZEw6eLvcy';
            $consumer_secrete = 's9vG72rqVn01Z4243aJTFNfcyuRX0MczOBbuc6l39krrAuLWRr';
            $access_token = '997384398-WteL6dkEXZsmAcaNXJjzfC7pHT3KBffWzqQjInJl';
            $access_token_secrete = 'rCIlZmWKt4R4pbVuiUpYNAKP9hqgmT9Rj0Lw07cPzDpn3';

            $twitter = new TwitterOAuth($consumer_key, $consumer_secrete, $access_token, $access_token_secrete);

            // Migrate over to SSL/TLS
            $twitter->ssl_verifypeer = true;
            // Load the Tweets
            $tweets = $twitter->get('statuses/user_timeline', ['screen_name' => $username, 'exclude_replies' => 'true', 'include_rts' => 'false', 'count' => $tweet_limit]);
            //dd($tweets);
            // Example output
            // Put this after fetching Tweets
            $twitter = '';
            // Create the HTML output
            //dd($tweets[0]->text);
            if (!empty($tweets)) {
                foreach ($tweets as $tweet) {
                    $twitter .= '<article>
           
            <p><i class="fa fa-twitter"></i> '.$tweet->text.'</p>
                <p><b>'.date('d-m-Y', strtotime($tweet->created_at)).'</p></b>
        </article>';
                }
            }
            echo $twitter;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
}
