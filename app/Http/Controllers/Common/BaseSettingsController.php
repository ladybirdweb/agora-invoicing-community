<?php

namespace App\Http\Controllers\Common;

use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BaseSettingsController extends Controller
{
    /**
     * Get the logged activity.
     */
    public function getNewEntry($properties, $model)
    {
        $properties = (array_key_exists('attributes', $properties->toArray()))
        ? ($model->properties['attributes']) : null;

        $display = [];

        if ($properties != null) {
            if (array_key_exists('parent', $properties)) {
                unset($properties['parent']);
            }
            foreach ($properties as $key => $value) {
                $display[] = '<strong>'.'ucfirst'($key).'</strong>'.' : '.$value.'<br/>';
            }
            $updated = (count($properties) > 0) ? implode('', $display) : '--';

            return $updated;
        } else {
            return '--';
        }
    }

    /**
     * Get the older Entries.
     */
    public function getOldEntry($data, $model)
    {
        $oldData = '';
        $oldData = (array_key_exists('old', $data->toArray())) ? ($model->properties['old']) : null;
        if ($oldData != null) {
            if ((count($oldData) > 0)) {
                foreach ($oldData as $key => $value) {
                    $display[] = '<strong>'.'ucfirst'($key).'</strong>'.' : '.$value.'<br/>';
                }
            }
            $old = (count($oldData) > 0) ? implode('', $display) : '--';

            return $old;
        } else {
            return '--';
        }
    }

    /**
     * Get Date.
     */
    public function getDate($dbdate)
    {
        $created = new DateTime($dbdate);
        $tz = \Auth::user()->timezone()->first()->name;
        $created->setTimezone(new DateTimeZone($tz));
        $date = $created->format('M j, Y, g:i a ');
        $newDate = $date;

        return $newDate;
    }

      public function destroyEmail(Request $request)
    {
        try {
            $ids = $request->input('select');
            if (!empty($ids)) {
                foreach ($ids as $id) {
                    $email = \DB::table('email_log')->where('id', $id)->delete();
                    if ($email) {
                        // $email->delete();
                    } else {
                        echo "<div class='alert alert-danger alert-dismissable'>
                        <i class='fa fa-ban'></i>

                        <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '.
                        /* @scrutinizer ignore-type */     \Lang::get('message.failed').'

                        <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                            './* @scrutinizer ignore-type */\Lang::get('message.no-record').'
                    </div>';
                        //echo \Lang::get('message.no-record') . '  [id=>' . $id . ']';
                    }
                }
                echo "<div class='alert alert-success alert-dismissable'>
                        <i class='fa fa-ban'></i>
                        <b>"./* @scrutinizer ignore-type */\Lang::get('message.alert').'!</b> '
                        ./* @scrutinizer ignore-type */\Lang::get('message.success').'
                        <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                            './* @scrutinizer ignore-type */ \Lang::get('message.deleted-successfully').'
                    </div>';
            } else {
                echo "<div class='alert alert-danger alert-dismissable'>
                        <i class='fa fa-ban'></i>
                        <b>"./* @scrutinizer ignore-type */ \Lang::get('message.alert').
                        '!</b> './* @scrutinizer ignore-type */\Lang::get('message.failed').'
                        <button type=button class=close data-dismiss=alert aria-hidden=true>&times;</button>
                            './* @scrutinizer ignore-type */ \Lang::get('message.select-a-row').'
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
