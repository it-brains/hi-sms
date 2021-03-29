# HiSMS
This is a package to integrate [HiSMS](https://www.hisms.ws) with Laravel.

Documentation is on [manual page](https://www.hisms.ws/manual.php) that send us to [API pdf](https://www.hisms.ws/uploads/api.pdf). 

# Installation
Require this package with composer.

```shell
composer require it-brains/hi-sms
```

You have to fill in your credentials to environment variables:
```
HISMS_USERNAME=
HISMS_PASSWORD=
HISMS_SENDER_NAME=
```

## Usage

You can now add messages using the Facade:

```php
HiSMS::getBalance();
$smsId = HiSMS::send($number, $message);
$smsId = HiSMS::sendBulk([$number1, $number2], $message);
```

or use DI:

```php
use ITBrains\HiSMS\HiSMSClient;

...

function sendSms(HiSMSClient $sms) 
{
    $sms->getBalance();
    $smsId = $sms->send($number, $message);
    $smsId = $sms->sendBulk([$number1, $number2], $message);
}

...
```

## Testing
If you need to mock SMSes on testing then you can just change your driver to anything not equal to 'hisms' on .env:
```
HISMS_DRIVER=hisms_faker
```
