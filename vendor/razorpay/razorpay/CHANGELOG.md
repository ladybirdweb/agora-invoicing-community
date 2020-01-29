# Change Log

Changelog for Razorpay-PHP SDK. Follows [keepachangelog.com](https://keepachangelog.com/en/1.0.0/) for formatting.

## Unreleased

## [2.5.0][2.5.0] - 2019-05-20

### Added

-   Adds support for payment edit API [[#100](https://github.com/razorpay/razorpay-php/pull/100)]

## [2.4.0-beta][2.4.0-beta] - 2018-11-28

### Changed

-   Upgrades [requests](https://github.com/rmccue/Requests/) to v1.7. [[#89](https://github.com/razorpay/razorpay-php/pull/89)]
-   Enforces TLS1.1+ for all requests. Workaround for a bug in RHEL 6. [[#76](https://github.com/razorpay/razorpay-php/pull/76)]

## [2.3.0][2.3.0] - 2018-09-15

### Added

-   Add parameters to Subscription Cancel API
-   Support for fetching Settlements

## [2.2.1][2.2.1] - 2018-05-28

### Added

-   Support for fetching all customer entities

## [2.2.0][2.2.0] - 2017-10-23

### Added

-   Support for VirtualAccount entity
-   Support for Subscriptions

## [2.1.0][2.1.0] - 2017-10-10

### Added

-   Support for new actions(cancel, notifyBy, edit, issue, delete) on invoices
-   Removes PHP 5.3 from list of versions to test build against

## [2.0.2][2.0.2] - 2017-08-03

### Added

-   Support for creating and fetching Transfers
-   Support for creating Reversals on transfers

## [2.0.1][2.0.1] - 2017-07-31

### Fixed

-   Webhook signature verification
-   Conditional require of Request class

## [2.0.0][2.0.0] - 2017-03-07

### Added

-   Support for custom Application header
-   Support for card entity
-   Support for Webhook and Order Signature verification
-   Support for direct refund creation via Razorpay\Api\Refund::create()
-   Support for Utility functions via Razorpay\Api\Utility::verifyPaymentSignature and Razorpay\Api\Utility::verifyWebhookSignature
-   Support for case insensitive error codes
-   Support for 2xx HTTP status codes

### Changed

-   Razorpay\Api\Payment::refunds() now returns a Razorpay\Api\Collection object instead of Razorpay\Api\Refund object
-   Razorpay\Api\Api::$baseUrl, Razorpay\Api\Api::$key and Razorpay\Api\Api::$secret are now `protected` instead of `public`

## [1.2.9][1.2.9] - 2017-01-03

### Added

-   Support for creating and fetching Invoices

## [1.2.8][1.2.8] - 2016-10-12

### Added

-   Support for Customer and Token entities

## [1.2.7][1.2.7] - 2016-09-21

### Added

-   Increases the request timeout to 30 seconds for all requests.

## [1.2.6][1.2.6] - 2016-03-28

### Added

-   Adds better tracing when client is not able to recognize server response.

## [1.2.5][1.2.5] - 2016-03-28

### Added

-   Add support for overriding request headers via setHeader

## [1.2.3][1.2.3] - 2016-02-24

### Added

-   Add support for Orders

## [1.2.2][1.2.2] - 2016-02-17

### Changed

-   Razorpay\Api\Request::checkErrors is now `protected` instead of `private`
-   The final build is now leaner and includes just requests, instead of entire vendor directory

## [1.2.1][1.2.1] - 2016-01-21

### Added

-   Add version.txt in release with current git tag
-   This changelog file
-   `Api\Request::getHeaders()` method

## [1.2.0][1.2.0] - 2015-10-23

### Added

-   Add version string to User Agent

### Changed

-   New release process that pushes pre-packaged zip files to GitHub

## 1.0.0 - 2015-01-18

### Added

-   Initial Release

[unreleased]: https://github.com/razorpay/razorpay-php/compare/2.5.0...HEAD
[1.2.1]: https://github.com/razorpay/razorpay-php/compare/1.2.0...1.2.1
[1.2.0]: https://github.com/razorpay/razorpay-php/compare/1.1.0...1.2.0
[1.2.2]: https://github.com/razorpay/razorpay-php/compare/1.2.1...1.2.2
[1.2.3]: https://github.com/razorpay/razorpay-php/compare/1.2.2...1.2.3
[1.2.4]: https://github.com/razorpay/razorpay-php/compare/1.2.3...1.2.4
[1.2.5]: https://github.com/razorpay/razorpay-php/compare/1.2.4...1.2.5
[1.2.6]: https://github.com/razorpay/razorpay-php/compare/1.2.5...1.2.6
[1.2.7]: https://github.com/razorpay/razorpay-php/compare/1.2.6...1.2.7
[1.2.8]: https://github.com/razorpay/razorpay-php/compare/1.2.7...1.2.8
[1.2.9]: https://github.com/razorpay/razorpay-php/compare/1.2.8...1.2.9
[2.0.0]: https://github.com/razorpay/razorpay-php/compare/1.2.9...2.0.0
[2.0.1]: https://github.com/razorpay/razorpay-php/compare/2.0.0...2.0.1
[2.0.2]: https://github.com/razorpay/razorpay-php/compare/2.0.1...2.0.2
[2.1.0]: https://github.com/razorpay/razorpay-php/compare/2.0.2...2.1.0
[2.2.0]: https://github.com/razorpay/razorpay-php/compare/2.1.0...2.2.0
[2.2.1]: https://github.com/razorpay/razorpay-php/compare/2.2.0...2.2.1
[2.3.0]: https://github.com/razorpay/razorpay-php/compare/2.2.1...2.3.0
[2.4.0-beta]: https://github.com/razorpay/razorpay-php/compare/2.3.0...2.4.0-beta
[2.5.0]: https://github.com/razorpay/razorpay-php/compare/2.4.0-beta...2.5.0
