<?php

Schema::connection('billinglocal')->create('coupons', function ($table) {
	$table->increments('id');
	$table->string('code');
	$table->integer('percent_off')->nullable();
	$table->integer('amount_off')->nullable();
	$table->integer('duration_in_months')->nullable();
	$table->timestamps();
	$table->softDeletes();
	
	$table->unique('code', 'coupon_code');
});
Schema::connection('billinglocal')->create('customers', function ($table) {
	$table->increments('id');
	$table->integer('coupon_id')->nullable();
	$table->string('email')->nullable();
	$table->text('description')->nullable();
	$table->timestamps();
	$table->softDeletes();
	
	$table->index('email', 'customer_email');
});
Schema::connection('billinglocal')->create('cards', function ($table) {
	$table->increments('id');
	$table->integer('customer_id')->unsigned();
	$table->string('last4')->nullable();
	$table->string('brand')->nullable();
	$table->string('exp_month')->nullable();
	$table->string('exp_year')->nullable();
	$table->string('name')->nullable();
	$table->string('address_line1')->nullable();
	$table->string('address_line2')->nullable();
	$table->string('address_city')->nullable();
	$table->string('address_state')->nullable();
	$table->string('address_zip')->nullable();
	$table->string('address_country')->nullable();
	$table->timestamps();
	$table->softDeletes();
	
	$table->index('customer_id', 'customer_cards');
});
Schema::connection('billinglocal')->create('plans', function ($table) {
	$table->increments('id');
	$table->string('key');
	$table->string('name');
	$table->integer('amount')->default(0);
	$table->string('interval')->default('monthly');
	$table->integer('trial_period_days')->nullable();
	$table->timestamps();
	$table->softDeletes();
	
	$table->unique('key', 'plan_key');
});
Schema::connection('billinglocal')->create('invoices', function ($table) {
	$table->increments('id');
	$table->integer('customer_id')->unsigned();
	$table->integer('subscription_id')->unsigned()->nullable();
	$table->tinyInteger('closed')->default(0);
	$table->tinyInteger('paid')->default(0);
	$table->integer('coupon_id')->nullable();
	$table->timestamp('period_started_at')->nullable();
	$table->timestamp('period_ends_at')->nullable();
	$table->timestamps();
	$table->softDeletes();
	
	$table->unique(array('customer_id', 'period_ends_at'), 'customer_invoices');
});
Schema::connection('billinglocal')->create('invoice_items', function ($table) {
	$table->increments('id');
	$table->integer('invoice_id')->unsigned();
	$table->integer('subscription_id')->unsigned()->nullable();
	$table->integer('amount')->default(0);
	$table->text('description')->nullable();
	$table->timestamp('period_started_at')->nullable();
	$table->timestamp('period_ends_at')->nullable();
	$table->integer('quantity')->default(1);
	$table->timestamps();
	$table->softDeletes();
	
	$table->unique('invoice_id', 'invoice_id_items');
});
Schema::connection('billinglocal')->create('subscriptions', function ($table) {
	$table->increments('id');
	$table->integer('customer_id')->unsigned();
	$table->integer('plan_id')->unsigned();
	$table->integer('card_id')->unsigned()->nullable();
	$table->integer('coupon_id')->nullable();
	$table->integer('quantity')->default(1);
	$table->timestamp('trial_ends_at')->nullable();
	$table->timestamp('cancel_at')->nullable();
	$table->timestamps();
	$table->softDeletes();
	
	$table->index('customer_id', 'customer_subscriptions');
});
Schema::connection('billinglocal')->create('charges', function ($table) {
	$table->increments('id');
	$table->integer('customer_id')->unsigned();
	$table->integer('card_id')->unsigned()->nullable();
	$table->integer('amount');
	$table->tinyInteger('paid')->default(0);
	$table->tinyInteger('refunded')->default(0);
	$table->tinyInteger('captured')->default(0);
	$table->text('description')->nullable();
	$table->timestamps();
	$table->softDeletes();
	
	$table->index('customer_id', 'customer_charges');
});
