## magento2-order-tracking-notification
___
Simple Magento 2 module that triggers an order shipment notification to the customer when tracking information is added via the REST API.

A new boolean field `notify` is added in the `extension_attributes` parameter of the payload for the [shipment/track](https://magento.redoc.ly/2.4.5-admin/tag/shipmenttrack#operation/PostV1ShipmentTrack) endpoint.

Example:
```
{
  "entity": {
    "order_id": 1,
    "parent_id": 1,
    "weight": 0,
    "qty": 0,
    "description": "string",
    "extension_attributes": {
        "notify": true
    },
    "track_number": "string",
    "title": "string",
    "carrier_code": "string"
  }
}
```
### Requirements
* PHP 7.4
* Magento 2.4.2 or Magento 2.4.4

### Installation
Installation can be done via composer by running `composer require codingdaniel/magento2-order-tracking-notification`
