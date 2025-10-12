<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Exception\MessagingException;

class FirebaseService
{
    private $messaging;

    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount([
                'type' => 'service_account',
                'project_id' => env('FIREBASE_PROJECT_ID', 'ampush-iot'),
                'private_key_id' => env('FIREBASE_PRIVATE_KEY_ID', '769a86c714ee7f9ec631f74ba626acda7c18adab'),
                'private_key' => env('FIREBASE_PRIVATE_KEY', '-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC9hqyPESQJJ4U4\netJu+6kIEO89JScqcT5RXFFwsyu/M02RoFXyxnIBYLqkGWvzKcQL/b3TcwxkzoHX\nen5GakLfcI48LGBr35diGXE3TGJHETUuU8A36BvVlP1i1JzH58bmKsN2VoPTbPwC\nSQDD9OcEbXINzG4wO3kF6YyD646snoTojq9bfWCzz6tsT8CDHhigQK88vwJgFV92\nEXc/zDi/JcpbY0P0th4RE4UsGHMHZOj9f1KopLAG6nuRt4Em3oIdWF2NTUhguEmH\n1267vXnGpGdm4XvTxQ/UfW0jXk5qlkoU0CzWt/W/RodpdqvljlT58uTQy1YIa6hu\nut/tVCnlAgMBAAECggEADgcB+LvNdZJT0/c+jHbQDwKxMPlx5ucHSakiIPaDOiKk\nrtmAYFiLjGpkSKwrVyMZTsQ6xhlENVDV1No3hDmDVhDOrN729ael8h2NHt6RK+Ph\nklp3W1vwoB6ny2Zjl+XMd5wk0o/QMCLnRbmxVro7//ykE1KKZEOpRISsQQD7UsTE\ndzMHsBEnAPGG0qvQ4URBR7aaqZyS192XrT/hozhpA1qAmu4jlraJDPsQ4jAYLxJz\nGEN9I6X9OtUfgz6hvQZEG9Dact/bmnd514cxQbnbV3zUhM3V8Lohcc5tZG5BAoRw\nNeZ845PJKrPAa3cQ0tMaiT7TVzXCo1on1ISS/V67aQKBgQDovxR+1Ob4oHM5LbPT\ndii4RZ8eLcQU6LexLu43KggKfyG7vpypo5WvVbgcgmjao0nBoOO13adLH7/ktxpn\nvnwiYdkrVQQ6LHn39QkSyCwj2oHgteDQIYrn3TucRTEs7fYQ4twFuf1i6ID8KJCP\n9gblACHBEKi+VCj56iK+DdVo7QKBgQDQdido/x8T+73inmItIGQWUpI0zAvZdmkZ\ns3AQjKc09fkbpcQL59InjV5qXn0JgXTRV2DmC3aqNpTuIwMMSw2aN+dGvzhrLQfg\nO/tAzCi/7dvQLlED8fFrjgI0XD1xLMAY+7J3EzF8OF2FxVXJtvJtrwD7ZEeq8txV\nMTc/Ciz92QKBgQCCzA9aj48VniXitjpe1gJgPFAFh7awAXBp1HPu8GIAdB2jAqXL\n97CBDm0fKHKAnE8wz5fodp4za65NfFEMiFH+iHqhDXAIuUH6BOyKb4/OvldKzyt+\nC7uiPgPn+EKAe2JTbwoy9ajeUsdZ7fn/zUVmoEJX22LZQzab6+aGZAPQqQKBgQC1\n5GK+2tzjMuWct1YyyfCLCcFJEEHGnetW4ZsG1bOQIpAZ76oAOWbF3DRl28x+Xtbv\nUq7aC2afXsDUiPg/4b0cs2q58F/qJICax7uT7pAf6AvEuqU2LAXbMy35QgLanZGA\nOx6dh8HGAeiYsHcKavddfTX+JKHkJ8TZEPiDqP1ZAQKBgFflLDsmq3tsQC1MTwxz\ngXtszN5FlKR55FHSn5ufOq/NBc8l2XXGDyjiGww65LnJ8RSUp+rl+R1Z0rzrz5rk\nPOz+t/1qG+Z2WbL0IzVUI0cDiJ0/4DjRfolY4kuphqVhVdoM1oatwInWtbfN1xYU\nTY2PAt7B2RfmV9zHVHvftf95\n-----END PRIVATE KEY-----\n'),
                'client_email' => env('FIREBASE_CLIENT_EMAIL', 'firebase-adminsdk-fbsvc@ampush-iot.iam.gserviceaccount.com'),
                'client_id' => env('FIREBASE_CLIENT_ID', '108047555236117283705'),
                'auth_uri' => env('FIREBASE_AUTH_URI', 'https://accounts.google.com/o/oauth2/auth'),
                'token_uri' => env('FIREBASE_TOKEN_URI', 'https://oauth2.googleapis.com/token'),
            ]);

        $this->messaging = $factory->createMessaging();
    }

    /**
     * Send notification to a single device
     */
    public function sendNotification($token, $title, $body, $data = [])
    {
        try {
            $notification = Notification::fromArray([
                'title' => $title,
                'body' => $body,
            ]);

            $message = CloudMessage::withTarget('token', $token)
                ->withNotification($notification)
                ->withData($data);

            return $this->messaging->send($message);
        } catch (MessagingException $e) {
            \Log::error('Firebase notification failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Send notification to multiple devices
     */
    public function sendToMultipleDevices($tokens, $title, $body, $data = [])
    {
        try {
            $notification = Notification::fromArray([
                'title' => $title,
                'body' => $body,
            ]);

            $message = CloudMessage::new()
                ->withNotification($notification)
                ->withData($data);

            return $this->messaging->sendMulticast($message, $tokens);
        } catch (MessagingException $e) {
            \Log::error('Firebase multicast notification failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Send notification to a topic
     */
    public function sendToTopic($topic, $title, $body, $data = [])
    {
        try {
            $notification = Notification::fromArray([
                'title' => $title,
                'body' => $body,
            ]);

            $message = CloudMessage::withTarget('topic', $topic)
                ->withNotification($notification)
                ->withData($data);

            return $this->messaging->send($message);
        } catch (MessagingException $e) {
            \Log::error('Firebase topic notification failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Send notification with custom Android config
     */
    public function sendWithAndroidConfig($token, $title, $body, $data = [], $androidConfig = [])
    {
        try {
            $notification = Notification::fromArray([
                'title' => $title,
                'body' => $body,
            ]);

            $message = CloudMessage::withTarget('token', $token)
                ->withNotification($notification)
                ->withData($data);

            if (!empty($androidConfig)) {
                $android = AndroidConfig::fromArray($androidConfig);
                $message = $message->withAndroidConfig($android);
            }

            return $this->messaging->send($message);
        } catch (MessagingException $e) {
            \Log::error('Firebase Android notification failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
