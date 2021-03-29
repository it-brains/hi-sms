<?php

namespace ITBrains\HiSMS;

use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleClient;
use InvalidArgumentException;
use ITBrains\HiSMS\Exceptions\HiSMSException;

class Client extends HiSMSClient
{
    private GuzzleClient $client;

    public $configs;

    private const SEND_SMS = 'send_sms';
    private const GET_BALANCE = 'get_balance';
    private const SCHEDULE_SMS_COUNT = 'schedule_sms_count';
    private const DELETE_SCHEDULE_SMS = 'delete_schedule_sms';

    private const AVAILABLE_FUNCTIONS = [
        self::SEND_SMS,
        self::GET_BALANCE,
        self::SCHEDULE_SMS_COUNT,
        self::DELETE_SCHEDULE_SMS,
    ];

    public function __construct()
    {
        $this->configs = config('hi-sms');
        $this->client = new GuzzleClient();
    }

    public function send(string $to, string $message, ?Carbon $scheduleAt = null)
    {
        $to = trim($to);
        $message = trim($message);

        if (!$to) {
            throw new HiSMSException($this->trans('to_number_is_required'));
        }

        if (!$message) {
            throw new HiSMSException($this->trans('message_is_required'));
        }

        $value = $this->executeRequest(
            self::SEND_SMS,
            array_merge(
                [
                    'numbers' => $to,
                    'sender' => $this->configs['sender_name'],
                    'message' => $message,
                ],
                $this->getScheduledOptions($scheduleAt)
            )
        );

        [, $smsId] = explode('-', $value);

        return $smsId;
    }

    public function getBalance(): int
    {
        $value = $this->executeRequest(self::GET_BALANCE);

        [, $balance] = explode(':', $value);

        return $balance;
    }

    public function getScheduleSmsCount(): int
    {
        $value = $this->executeRequest(self::SCHEDULE_SMS_COUNT);

        [, $count] = explode(':', $value);

        return $count;
    }

    public function deleteScheduleSms(): int
    {
        $value = $this->executeRequest(self::DELETE_SCHEDULE_SMS);

        [, $count] = explode(':', $value);

        return $count;
    }

    protected function executeRequest(string $method, array $params = []): string
    {
        if (!in_array($method, self::AVAILABLE_FUNCTIONS)) {
            throw new InvalidArgumentException("Unexpected '{$method}' function.");
        }

        $response = $this->client->request(
            'GET',
            $this->configs['api_endpoint'],
            [
                'query' => array_merge(
                    [
                        $method => '1',
                        'username' => $this->configs['username'],
                        'password' => $this->configs['password'],
                    ],
                    $params
                )
            ]
        );

        $value = $response->getBody()->getContents();

        $this->handleErrorValue($value);

        return $value;
    }

    protected function handleErrorValue(string $value)
    {
        $message = null;

        switch ($value) {
            case '1':
                $message = 'username_is_incorrect';
                break;
            case '2':
                $message = 'invalid_password';
                break;
            case '3':
                // 'has been sent' - so all is good
                break;
            case '4':
                $message = 'no_numbers';
                break;
            case '5':
                $message = 'no_message';
                break;
            case '6':
                $message = 'the_sender_is_wrong';
                break;
            case '7':
                $message = 'the_sender_is_not_activated';
                break;
            case '8':
                $message = 'has_forbidden_word';
                break;
            case '9':
                $message = 'there_is_no_credit';
                break;
            case '10':
                $message = 'wrong_date_format';
                break;
            case '11':
                $message = 'wrong_time_format';
                break;
            case '404':
                $message = 'not_all_required_parameters_are_entered';
                break;
            case '403':
                $message = 'the_number_of_allowed_attempts_exceeded';
                break;
            case '504':
                $message = 'account_disabled';
                break;
        }

        if ($message) {
            throw new HiSMSException($this->trans($message), $value);
        }
    }

    /**
     * @param Carbon|null $scheduleAt
     *
     * @return array
     */
    private function getScheduledOptions(?Carbon $scheduleAt): array
    {
        $scheduleOptions = [];

        if ($scheduleAt) {
            $scheduleOptions['date'] = $scheduleAt->format('Y-m-d');
            $scheduleOptions['time'] = $scheduleAt->format('H:i');
        }

        return $scheduleOptions;
    }

    private function trans($message): string
    {
        return trans("hi_sms::hi-sms.{$message}");
    }
}
