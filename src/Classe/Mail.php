<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    public function send($to_email, $to_name, $subject, $template, $vars = null)
    {
        $content = file_get_contents(dirname(__DIR__) . '/Mail/' . $template);

        if ($vars) {
            foreach ($vars as $key => $var) {
                $content = str_replace('{' . $key . '}', $var, $content);
            }
        }

        $mj = new Client($_ENV['MJ_APIKEY_PUBLIC'], $_ENV['MJ_APIKEY_PRIVATE'], true, ['version' => 'v3.1']);

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $_ENV['ADMIN_EMAIL'],
                        'Name' => "La Fourchette Victorieuse"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'Subject' => $subject,
                    'TextPart' => "Greetings from Mailjet!",
                    'HTMLPart' => $content
                ]
            ]
        ];

        $mj->post(Resources::$Email, ['body' => $body]);
    }

    public function contact($to_name, $subject, $template, $vars = null)
    {
        $content = file_get_contents(dirname(__DIR__) . '/Mail/' . $template);

        if ($vars) {
            foreach ($vars as $key => $var) {
                $content = str_replace('{' . $key . '}', $var, $content);
            }
        }

        $mj = new Client($_ENV['MJ_APIKEY_PUBLIC'], $_ENV['MJ_APIKEY_PRIVATE'], true, ['version' => 'v3.1']);

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $_ENV['ADMIN_EMAIL'],
                        'Name' => "La Fourchette Victorieuse"
                    ],
                    'To' => [
                        [
                            'Email' => $_ENV['ADMIN_EMAIL'],
                            'Name' => $to_name
                        ]
                    ],
                    'Subject' => $subject,
                    'TextPart' => "Greetings from Mailjet!",
                    'HTMLPart' => $content
                ]
            ]
        ];

        $mj->post(Resources::$Email, ['body' => $body]);
    }
}
