## payment verification

### Verify payment verification

```php
$api->utility->verifyPaymentSignature(array('razorpay_order_id' => $razorpayOrderId, 'razorpay_payment_id' => $razorpayPaymentId, 'razorpay_signature' => $razorpaySignature));
```

**Parameters:**


| Name  | Type      | Description                                      |
|-------|-----------|--------------------------------------------------|
| orderId*  | string | The id of the order to be fetched  |
| paymentId*    | string | The id of the payment to be fetched |
| signature* | string   | Signature returned by the Checkout. This is used to verify the payment. |

-------------------------------------------------------------------------------------------------------
### Verify subscription verification

```php
$api->utility->verifyPaymentSignature(array('razorpay_subscription_id' => $razorpaySubscriptionId, 'razorpay_payment_id' => $razorpayPaymentId, 'razorpay_signature' => $razorpaySignature));
```

**Parameters:**


| Name  | Type      | Description                                      |
|-------|-----------|--------------------------------------------------|
| subscriptionId*  | string | The id of the subscription to be fetched  |
| paymentId*    | string | The id of the payment to be fetched |
| signature* | string   | Signature returned by the Checkout. This is used to verify the payment. |

-------------------------------------------------------------------------------------------------------
### Verify paymentlink verification

```php
$api->utility->verifyPaymentSignature(array('razorpay_payment_link_id' => $razorpayPaymentlinkId, 'razorpay_payment_id' => $razorpayPaymentId, 'razorpay_payment_link_reference_id' => $razorpayPaymentLinkReferenceId, 'razorpay_payment_link_status' => $razorpayPaymentLinkStatus, 'razorpay_signature' => $razorpayPaymentLinkSignature));
```

**Parameters:**


| Name  | Type      | Description                                      |
|-------|-----------|--------------------------------------------------|
| razorpayPaymentlinkId*  | string | The id of the paymentlink to be fetched  |
| razorpayPaymentId*  | string | The id of the payment to be fetched  |
| razorpayPaymentLinkReferenceId*  | string |  A reference number tagged to a Payment Link |
| razorpayPaymentLinkStatus*  | string | Current status of the link  |
| razorpayPaymentLinkSignature*    | string | Signature returned by the Checkout. This is used to verify the payment. |

-------------------------------------------------------------------------------------------------------

**PN: * indicates mandatory fields**
<br>
<br>
