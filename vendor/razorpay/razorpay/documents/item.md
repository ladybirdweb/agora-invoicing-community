## items

### Create item

```php
$api->Item->create(array("name" => "Book / English August","description" => "An indian story, Booker prize winner.","amount" => 20000,"currency" => "INR"));
```

**Parameters:**

| Name            | Type    | Description                                                                  |
|-----------------|---------|------------------------------------------------------------------------------|
| name*          | string | Name of the item.                    |
| description        | string  | A brief description of the item.  |
| amount*         | integer  | Amount of the order to be paid     |
| currency*           | string  | Currency of the order. Currently only `INR` is supported.    |

**Response:**
```json
{
  "id": "item_7Oxp4hmm6T4SCn",
  "active": true,
  "name": "Book / English August",
  "description": "An indian story, Booker prize winner.",
  "amount": 20000,
  "currency": "INR"
}
```

-------------------------------------------------------------------------------------------------------

### Fetch all items

```php
$api->Item->all($options);
```
**Parameters:**

| Name  | Type      | Description                                      |
|-------|-----------|--------------------------------------------------|
| from  | timestamp | timestamp after which the item were created  |
| to    | timestamp | timestamp before which the item were created |
| count | integer   | number of item to fetch (default: 10)        |
| skip  | integer   | number of item to be skipped (default: 0)    |
| active   | boolean  | Possible values is `0` or `1` |

**Response:**
```json
{
  "entity": "collection",
  "count": 3,
  "items": [
    {
      "id": "item_7Oy8OMV6BdEAac",
      "active": true,
      "name": "Book / Ignited Minds",
      "description": null,
      "amount": 15000,
      "currency": "INR"
    },
    {
      "id": "item_7Oxp4hmm6T4SCn",
      "active": true,
      "name": "Book / English August",
      "description": "An indian story, Booker prize winner.",
      "amount": 20000,
      "currency": "INR"
    },
    {
      "id": "item_7OxoGnoxCuUKbo",
      "active": true,
      "name": "Book / English August",
      "description": null,
      "amount": 20000,
      "currency": "INR"
    }
  ]
}
```
-------------------------------------------------------------------------------------------------------
### Fetch particular item

```php
$api->Item->fetch($itemId);
```
**Parameters**

| Name     | Type   | Description                         |
|----------|--------|-------------------------------------|
| itemId* | string | The id of the item to be fetched |

**Response:**
```json
{
  "id": "item_7Oxp4hmm6T4SCn",
  "active": true,
  "name": "Book / English August",
  "description": "An indian story, Booker prize winner.",
  "amount": 20000,
  "currency": "INR"
}
```

-------------------------------------------------------------------------------------------------------

### Update item

```php
$api->Item->fetch($itemId)->edit(array("name" => "Book / Ignited Minds - Updated name!","description" => "New descirption too. :).","amount" => 20000,"currency" => "INR","active" => true
));
```
**Parameters**

| Name     | Type   | Description                         |
|----------|--------|-------------------------------------|
| itemId* | string | The id of the item to be fetched |
| name       | string | Name of the item.                    |
| description  | string  | A brief description of the item.  |
| amount         | integer  | Amount of the order to be paid     |
| currency           | string  | Currency of the order. Currently only `INR` is supported.    |
| active   | boolean  | Possible values is `0` or `1` |

**Response:**
```json
{
  "id": "item_7Oy8OMV6BdEAac",
  "active": true,
  "name": "Book / Ignited Minds - Updated name!",
  "description": "New descirption too. :)",
  "amount": 15000,
  "currency": "INR"
}
```
-------------------------------------------------------------------------------------------------------
### Delete item

```php
$api->Item->fetch($itemId)->delete();
```
**Parameters**

| Name     | Type   | Description                         |
|----------|--------|-------------------------------------|
| itemId* | string | The id of the item to be fetched |

**Response:**
```json
[]
```
-------------------------------------------------------------------------------------------------------

**PN: * indicates mandatory fields**
<br>
<br>
**For reference click [here](https://razorpay.com/docs/api/items)**
