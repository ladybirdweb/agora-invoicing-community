<?php

namespace Tests\Unit\Admin\User;

use App\Comment;
use App\Model\Order\Invoice;
use App\Model\Order\Order;
use App\Model\Product\Product;
use App\User;
use Tests\DBTestCase;

class SoftDeleteControllerTest extends DBTestCase
{
    /** @group softDelete */
    public function test_softDeletedUsers_checkUserIsSoftDeleted()
    {
        $this->withoutMiddleware();
        $user = factory(User::class)->create();
        $user->delete();
        $data = $this->call('GET', 'soft-delete');
        $idAfterDelete = json_decode($data->getContent())->data[0]->id;
        $this->assertEquals($user->id, $idAfterDelete);
        $this->assertSoftDeleted('users', ['id'=>$user->id, 'email' => $user->email]);
    }

    /** @group softDelete */
    public function test_restoreUser_checkSoftDeletedUserIsRestored()
    {
        $this->withoutMiddleware();
        $user = factory(User::class)->create();
        $user->delete();
        $data = $this->call('GET', 'clients/'.$user->id.'/restore');
        $data->assertSessionHas('success');
    }

    /** @group softDelete */
    public function test_permanentDeleteUser_deleteUserPermanently()
    {
        $this->withoutMiddleware();
        $user = factory(User::class)->create();
        $user->delete();
        $data = $this->call('DELETE', 'permanent-delete-client', ['select'=>[$user->id]]);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /** @group softDelete */
    public function test_permanentDeleteUser_deleteInvoiceOrderCommnetPermanently()
    {
        $this->withoutMiddleware();
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $product = Product::create(['name'=>'Helpdesk']);
        $invoice = Invoice::create(['user_id'=>$user1->id, 'number'=>'234435']);
        $comment = Comment::create(['user_id'=>$user2->id, 'updated_by_user_id'=>$user1->id, 'description'=>'TesComment']);
        $order = Order::create(['client'=> $user1->id, 'order_status' => 'executed', 'product'=> $product->id]);
        $user1->delete();
        $data = $this->call('DELETE', 'permanent-delete-client', ['select'=>[$user1->id]]);
        $this->assertDatabaseMissing('users', ['id' => $user1->id]);
        $this->assertDatabaseMissing('invoices', ['user_id' => $user1->id]);
        $this->assertDatabaseMissing('orders', ['client' => $user1->id]);
        $this->assertDatabaseMissing('comments', ['updated_by_user_id' => $user1->id]);
    }
}
