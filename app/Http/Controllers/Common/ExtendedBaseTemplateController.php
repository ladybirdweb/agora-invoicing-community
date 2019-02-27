<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Bugsnag;

class ExtendedBaseTemplateController extends Controller
{
    public function popup($title, $body, $width = '897', $name = '', $modelid = '', $class = 'null', $trigger = false)
    {
        try {
            if ($modelid == '') {
                $modelid = $title;
            }
            if ($trigger == true) {
                $trigger = "<a href=# class=$class  data-toggle='modal' data-target=#edit".$modelid.'>'.$name.'</a>';
            } else {
                $trigger = '';
            }

            return $trigger."
                        <div class='modal fade' id=edit".$modelid.">
                            <div class='modal-dialog' style='width: ".$width."px;'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <button type='button' class='close' data-dismiss='modal'
                                         aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                        <h4 class='modal-title'>".$title."</h4>
                                    </div>
                                    <div class='modal-body'>
                                    ".$body."
                                    </div>
                                    <div class='modal-footer'>
                                   
                                        <button type=button id=close class='btn btn-default pull-left' 
                                        data-dismiss=modal>Close</button>
                                        <input type=submit class='btn btn-primary' value=".
                                        /* @scrutinizer ignore-type */
                                        \Lang::get('message.save').'>
                                    </div>
                                    '.\Form::close().'
                                </div>
                            </div>
                        </div>';
        } catch (\Exception $ex) {
            Bugsnag::notifyException($ex);

            throw new \Exception($ex->getMessage());
        }
    }
}
