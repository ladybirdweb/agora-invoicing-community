<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Model\Common\Country;
use App\Model\Common\Mailchimp\MailchimpField;
use App\Model\Common\Mailchimp\MailchimpFieldAgoraRelation;
use App\Model\Common\Mailchimp\MailchimpGroup;
use App\Model\Common\Mailchimp\MailchimpGroupAgoraRelation;
use App\Model\Common\Mailchimp\MailchimpLists;
use App\Model\Common\Mailchimp\MailchimpSetting;
use App\Model\Common\Setting;
use App\Model\Product\Product;
use App\User;
use Exception;
use Illuminate\Http\Request;

class MailChimpController extends Controller
{
    protected $mail_api_key;
    protected $mailchimp;
    protected $mailchimp_field_model;
    protected $mailchimp_set;
    protected $list_id;
    protected $lists;
    protected $relation;

    public function __construct()
    {
        $mailchimp_set = new MailchimpSetting();
        $this->mailchimp_set = $mailchimp_set->firstOrFail();
        $this->mail_api_key = $this->mailchimp_set->api_key;
        $this->list_id = $this->mailchimp_set->list_id;
        $this->product_group_id = $this->mailchimp_set->group_id_products;
        $this->is_paid_group_id = $this->mailchimp_set->group_id_is_paid;

        $mailchimp_filed_model = new MailchimpField();
        $this->mailchimp_field_model = $mailchimp_filed_model;

        $lists = new MailchimpLists();
        $this->lists = $lists;

        $groups = new MailchimpGroup();
        $this->groups = $groups;

        $relation = new MailchimpFieldAgoraRelation();
        $this->relation = $relation->firstOrFail();

        $groupRelation = new MailchimpGroupAgoraRelation();
        $this->groupRelation = $groupRelation;

        $this->mailchimp = new \Mailchimp\Mailchimp($this->mail_api_key);
    }

    public function getLists()
    {
        try {
            $result = $this->mailchimp->request('lists');

            return $result;
        } catch (Exception $ex) {
            dd($ex);

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getListById()
    {
        try {
            $result = $this->mailchimp->request("lists/$this->list_id");

            return $result;
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function addSubscriber($email)
    {
        try {
            $merge_fields = $this->field($email);
            $interestGroupIdForNo = $this->relation->is_paid_no; //Interest GroupId for IsPaid Is No
              $interestGroupIdForYes = $this->relation->is_paid_yes; //Interest GroupId for IsPaid Is Yes
            $result = $this->mailchimp->post("lists/$this->list_id/members", [
                'status'        => $this->mailchimp_set->subscribe_status,
                'email_address' => $email,
                'merge_fields'  => $merge_fields,

                'interests'         => [$interestGroupIdForNo => true, $interestGroupIdForYes=>false],

            ]);

            return $result;
        } catch (Exception $ex) {
            $exe = json_decode($ex->getMessage(), true);
            if ($exe['status'] == 400) {
                throw new Exception("$email is already subscribed to newsletter", 400);
            }
        }
    }

    //Update to Mailchimp For Free Product
    public function updateSubscriberForFreeProduct($email, $productid)
    {
        try {
            $merge_fields = $this->field($email);
            $interestGroupIdForNo = $this->relation->is_paid_no; //Interest GroupId for IsPaid Is No
              $interestGroupIdForYes = $this->relation->is_paid_yes; //Interest GroupId for IsPaid Is Yes
              $productGroupId = $this->groupRelation->where('agora_product_id', $productid)
              ->pluck('mailchimp_group_cat_id')->first();
            $hash = md5($email);
            $result = $this->mailchimp->patch("lists/$this->list_id/members/$hash", [
                 'interests'         => [$interestGroupIdForNo => true, $interestGroupIdForYes=>false, $productGroupId =>true],
                 //refer to https://us7.api.mailchimp.com/playground
              ]);
        } catch (Exception $ex) {
            $exe = json_decode($ex->getMessage(), true);
        }
    }

    //Update to Mailchimp For Paid Product
    public function updateSubscriberForPaidProduct($email, $productid)
    {
        try {
            $merge_fields = $this->field($email);
            $interestGroupIdForNo = $this->relation->is_paid_no; //Interest GroupId for IsPaid Is No
              $interestGroupIdForYes = $this->relation->is_paid_yes; //Interest GroupId for IsPaid Is Yes
               $productGroupId = $this->groupRelation->where('agora_product_id', $productid)
              ->pluck('mailchimp_group_cat_id')->first();

            $hash = md5($email);
            $result = $this->mailchimp->patch("lists/$this->list_id/members/$hash", [
                 'interests' => [$interestGroupIdForNo => false, $interestGroupIdForYes=>true, $productGroupId =>true],

                 //refer to https://us7.api.mailchimp.com/playground
              ]);
        } catch (Exception $ex) {
            dd($ex);
            $exe = json_decode($ex->getMessage(), true);
        }
    }

    public function addSubscriberByClientPanel(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        try {
            $email = $request->input('email');
            $result = $this->mailchimp->post("lists/$this->list_id/members", [
                'status'        => $this->mailchimp_set->subscribe_status,
                'email_address' => $email,

            ]);

            return redirect()->back()->with('success', 'email added to mailchimp');
        } catch (Exception $ex) {
            $exe = json_decode($ex->getMessage(), true);
            if ($exe['status'] == 400) {
                $error = "$email is already subscribed to newsletter";

                return redirect()->back()->with('warning', $error);
            }

            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function field($email)
    {
        try {
            $user = new User();
            $setting = new Setting();
            $user = $user->where('email', $email)->first();
            $country = Country::where('country_code_char2', $user->country)->pluck('nicename')->first();
            if ($user) {
                $fields = ['first_name', 'last_name', 'company', 'mobile',
                 'address', 'town', 'country', 'state', 'zip', 'active', 'role', 'source', ];
                $relation = $this->relation;
                $merge_fields = [];
                foreach ($fields as $field) {
                    if ($relation->$field) {
                        $merge_fields[$relation->$field] = $user->$field;
                    }
                }
                $merge_fields[$relation->country] = $country;
                $merge_fields[$relation->source] = $setting->findorFail(1)->title;

                return $merge_fields;
            } else {
                return redirect()->back()->with('fails', 'user not found');
            }
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function getMergeFields()
    {
        try {
            $result = $this->mailchimp->get("lists/$this->list_id/merge-fields");

            return $result;
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function addFieldsToAgora()
    {
        try {
            /** @scrutinizer ignore-call */
            $fields = $this->getMergeFields($this->list_id);

            $mailchimp_field_in_agora = $this->mailchimp_field_model->get();
            if (count($mailchimp_field_in_agora) > 0) {
                foreach ($mailchimp_field_in_agora as $field) {
                    $field->delete();
                }
            }
            foreach ($fields['merge_fields'] as $key => $value) {
                $merge_id = $value->merge_id;
                $name = $value->name;
                $type = $value->type;
                $required = $value->required;
                $list_id = $value->list_id;
                $tag = $value->tag;

                $this->mailchimp_field_model->create([
                    'merge_id' => $merge_id,
                    'tag'      => $tag,
                    'name'     => $name,
                    'type'     => $type,
                    'required' => $required,
                    'list_id'  => $list_id,
                ]);
            }
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function addListsToAgora()
    {
        try {
            $lists = $this->getLists();
            $agora_lists = $this->lists->get();
            if (count($agora_lists) > 0) {
                foreach ($agora_lists as $agora) {
                    $agora->delete();
                }
            }
            foreach ($lists['lists'] as $list) {
                $name = $list->name;
                $list_id = $list->id;
                $this->lists->create([
                    'name'    => $name,
                    'list_id' => $list_id,
                ]);
            }
            //return redirect()->back()->with('success', \Lang::get('message.mailchimp-list-added-to-agora'));
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function mailChimpSettings()
    {
        try {
            $set = $this->mailchimp_set;
            $lists = $this->lists->pluck('name', 'list_id')->toArray();

            return view('themes.default1.common.mailchimp.settings', compact('set', 'lists'));
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function postMailChimpSettings(Request $request)
    {
        $this->validate($request, [
            'api_key' => 'required',
            //'list_id'=>'required',
        ]);

        try {
            $this->mailchimp_set->fill($request->input())->save();
            $this->addListsToAgora();

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function mapField()
    {
        try {
            $model = $this->relation;
            $model2 = $this->groupRelation;
            $this->addFieldsToAgora();

            $mailchimp_fields = $this->mailchimp_field_model
            ->where('list_id', $this->list_id)->pluck('name', 'tag')->toArray();
            $agoraProducts = Product::pluck('name', 'id')->toArray();

            // $ProductsMailchimp = $this->mailchimp->get("lists/$this->list_id/interest-categories/$this->product_group_id/interests");

            // foreach ($ProductsMailchimp['interests'] as $mailchimpProduct) {
            //    $mailchimpProductsList[] = ([$mailchimpProduct->name => $mailchimpProduct->id]);
            // }
            //  $oneDimensionalArray = call_user_func_array('array_merge', $mailchimpProductsList);

            $mailchimpProducts = $this->mailchimp->get("lists/$this->list_id/interest-categories");

            $selectedProducts = MailchimpGroupAgoraRelation::select('agora_product_id', 'mailchimp_group_cat_id')->orderBy('id', 'asc')->get()->toArray();

            $allGroups = $this->mailchimp->get("lists/$this->list_id/interest-categories"); //Get all the groups(interest-categories for a list)
            foreach ($allGroups['categories']  as $key => $value) {
                $display[] = (['id'=> $value->id, 'title'=>$value->title]);
            }

            // dd($selectedProduct);

            $this->addProductInterestFieldsToAgora(); //add all the fields in Product Section of Groups to the db
            // this->addIsPaidInterestFieldsToAgora();

            $group_fields = $this->groups->where('list_id', $this->list_id)
          ->select('category_name', 'category_option_id', 'category_id')->get()->toArray();
            // dd($group_fields[0]);
            $relations = MailchimpGroupAgoraRelation::where('id', '!=', 0)
          ->select('agora_product_id', 'mailchimp_group_cat_id')
          ->orderBy('id', 'asc')->get()->toArray();
            $productList = [];
            $categoryList = [];
            if (count($relations) != 0) {
                foreach ($relations as $key => $value) {
                    $categoryList[] = $this->groups->where('category_option_id', $value['mailchimp_group_cat_id'])->pluck('category_name')->first();
                    $productList[] = Product::where('id', $value['agora_product_id'])->pluck('name')->first();
                    // code...
                }
                // dd(count($categoryList));
         // dd($productList);
            }

            // $relations = MailchimpGroupAgoraRelation::where('id', '!=', 0)->orderBy('id', 'asc')->get()->toArray();
            // dd($relations);
            //

            return view('themes.default1.common.mailchimp.map', compact('mailchimp_fields', 'model2', 'model', 'agoraProducts', 'display', 'selectedProducts', 'relations', 'group_fields', 'categoryList', 'productList'));
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function addInterestFieldsToAgora(Request $request, $value)
    {
        $groupInterests = $this->mailchimp->get("lists/$this->list_id/interest-categories/$value/interests?count=20");
        echo '<option value="">Choose a Category</option>';
        if (count($groupInterests) > 0) {
            foreach ($groupInterests['interests'] as $key=>$value) {
                $fields[] = (['category_id' => $value->category_id,
                'list_id'                   => $value->list_id,
                'category_option_id'        => $value->id,
                'category_option_name'      => $value->name,
            ]);
            }
            foreach ($fields as $field) {
                $selectedCategory = MailchimpGroupAgoraRelation::where('mailchimp_group_cat_id', $field['category_option_id'])->pluck('mailchimp_group_cat_id')->first();
                //   if( $selectedCategory){
                //     echo '<option selected= "selected" value='. $selectedCategory .'>'.$field['category_option_name'].'</option>';
                // }

                echo '<option value='.$field['category_option_id'].'>'.$field['category_option_name'].'</option>';

                // array_push($responseData, (['selectedCat'=>$selectedCategory, 'catId'=>$field['category_option_id'],'catName'=>$field['category_option_name']]
                  //  ));
            }
            // return $responseData;
        }

        // return $a;
    }

    public function addProductInterestFieldsToAgora()
    {
        $groupProductInterests = $this->mailchimp->get("lists/$this->list_id/interest-categories/$this->product_group_id/interests?count=20");
        $groupIsPaidInterests = $this->mailchimp->get("lists/$this->list_id/interest-categories/$this->is_paid_group_id/interests?count=20");
        //Now Merge interests of both the groups
        $groupInterests = (array_merge($groupProductInterests['interests'], $groupIsPaidInterests['interests']));
        //get all the fields for Product Group
        if (count($groupInterests) > 0) {
            foreach ($groupInterests as $key=>$value) {
                $category_id = $value->category_id;
                $list_id = $value->list_id;
                $category_option_id = $value->id;
                $category_option_name = $value->name;
                $this->groups->updateOrCreate([
                'category_id'        => $category_id,
                'list_id'            => $list_id,
                'category_option_id' => $category_option_id,
                'category_name'      => $category_option_name,
            ]);
            }
        }
    }

    public function postMapField(Request $request)
    {
        try {
            $this->relation->fill($request->input())->save();

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function postGroupMapField(Request $request)
    {
        try {
            MailchimpGroupAgoraRelation::where('id', '!=', 0)->delete();
            foreach ($request->row as $key => $value) {
                MailchimpGroupAgoraRelation::create(['agora_product_id'=> $value[0],
             'mailchimp_group_cat_id'                                  => $value[1], ]);
            }

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }
}
