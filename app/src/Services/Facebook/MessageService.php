<?php
/**
 * @author <Akartis>
 * (c) akartis-dev <sitrakaleon23@gmail.com>
 * Do it with love
 */

namespace App\Services\Facebook;


use App\Entity\ErrorDetail;
use App\ObjectManager\EntityObjectManager;
use App\Services\Application\AppService;
use Curl\Curl;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Serializer\SerializerInterface;

class MessageService
{
    public function __construct(
        private SerializerInterface $serializer,
        private EntityObjectManager $em,
        private AppService $appService
    )
    {
    }

    /**
     * Send see and typing_on message
     * @param int $id
     */
    public function sendMarkSeen(int $id): void
    {
        $markSeen = [
            'recipient' => [
                'id' => $id
            ],
            'sender_action' => "mark_seen"
        ];

        $this->sendRequestWithoutRetry($markSeen);
    }

    /**
     * Handle request to send message
     * Try sending message 5 times
     *
     * @param $data
     */
    private function sendRequest($id, $data): bool
    {
        $client = new Client(['headers' => ['Content-Type' => 'application/json']]);
        $error = false;

        for ($i = 1; $i <= 10; $i++) {
            try {
                $client->request('POST', $_ENV['FB_URL'], [
                    'body' => $this->serializer->serialize($data, 'json')
                ]);

                break;
            } catch (GuzzleException $e) {
                if ($i === 5) {
                    $error = true;
                    $this->sendMessageInUser($id, "Nisy olana teo amin'ny fitadiavana ny hira :( , mangataka anao mba hamerina azafady.");
                }
            }
        }

        if ($error) {
            $errorDetail = (new ErrorDetail())->setDetail($e->getMessage())
                ->setOrigin($this->serializer->serialize($data, 'json'))
                ->setSearchTerm($this->appService->getSearchTerm());
            $this->em->save($errorDetail);
        }

        return true;
    }

    /**
     * Send message to facebook without retry
     */
    public function sendRequestWithoutRetry($data): void
    {
        $client = new Client(['headers' => ['Content-Type' => 'application/json']]);

        try {
            $client->request('POST', $_ENV['FB_URL'], [
                'body' => $this->serializer->serialize($data, 'json')
            ]);
        } catch (GuzzleException $e) {
            $errorDetail = (new ErrorDetail())->setDetail($e->getMessage())
                ->setOrigin($this->serializer->serialize($data, 'json'))
                ->setSearchTerm($this->appService->getSearchTerm());
            $this->em->save($errorDetail);
        }
    }

    public function sendTypingOn(int $id): void
    {
        $typingOn = [
            'recipient' => ['id' => $id],
            'sender_action' => "typing_on"
        ];

        $this->sendRequest($id, $typingOn);
    }

    /**
     * Sent normal message to user
     *
     * @param int $id
     * @param string $text
     */
    public function sendMessageInUser(int $id, string $text): void
    {
        $message = [
            'recipient' => ['id' => $id],
            'message' => [
                "text" => $text
            ]
        ];

        $this->sendRequestWithoutRetry($message);
    }

    /**
     * Send message with template
     * Create a template with musics
     *
     * @param int $id
     * @param $data
     * @throws \JsonException
     */
    public function sendYoutubeResultMessage(int $id, $data, bool $isVideo = false): bool
    {
        $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);

        if (count($data) < 1) {
            $this->sendMessageInUser($id, "Tsy nahita valiny aho tamin'ny lohanteny notadiavinao. 😶😶 ");

            return true;
        }

        $elements = [];
        foreach ($data as $video) {
            $elements[] = [
                "title" => $video['title'],
                "image_url" => $video['thumbnails'],
                "subtitle" => sprintf("Faharetany: %s", $video['duration']),
                "default_action" => [
                    "type" => "web_url",
                    "url" => "https://web.facebook.com/Namako-Bot-102269712007405",
                    "webview_height_ratio" => "tall"
                ],
                "buttons" => $isVideo ? [
                    [
                        "type" => "postback",
                        "title" => "360p",
                        "payload" => json_encode(['url' => $video['url'], 'type' => "MP4", 'quality' => 360], JSON_THROW_ON_ERROR),
                    ],
                    [
                        "type" => "postback",
                        "title" => "480p",
                        "payload" => json_encode(['url' => $video['url'], 'type' => "MP4", 'quality' => 480], JSON_THROW_ON_ERROR),
                    ]
                ] :
                    [
                        [
                            "type" => "postback",
                            "title" => "Mp3",
                            "payload" => $video['url'],
                        ]
                    ]

            ];
        }

        $message = [
            'recipient' => ['id' => $id],
            'message' => [
                "attachment" => [
                    "type" => "template",
                    "payload" => [
                        "template_type" => "generic",
                        "elements" => $elements
                    ]
                ]
            ]
        ];

        $this->sendRequestWithoutRetry($message);

        return true;
    }

    /**
     * Send file downloaded in message
     *
     * @param $id
     * @param $url
     */
    public function sendFileMessage(int $id, string $url, bool $remove = true): void
    {
        $file = curl_file_create($url);
        $data = [
            'recipient' => ['id' => $id],
            'message' => ['attachment' => ['type' => 'file', 'payload' => ['is_reusable' => true]]],
            'filedata' => $file
        ];

        $curl = new Curl();
        $curl->disableTimeout();
        $curl->post($_ENV['FB_URL'], $data);

        if ($remove) {
            unlink($url);
        }
    }

    /**
     * New welcome bot message
     *
     * @param int $id
     * @throws \JsonException
     */
    public function sendWelcomeBot(int $id): void
    {
        $message = [
            'recipient' => ['id' => $id],
            'message' => [
                "attachment" => [
                    "type" => "template",
                    "payload" => [
                        "template_type" => "generic",
                        "elements" => [
                            [
                                "title" => "Tongasoa eto amin'ny Namako bot",
                                "image_url" => "https://scontent.ftnr2-1.fna.fbcdn.net/v/t39.30808-6/275853065_308491904718517_6412891074985435079_n.jpg?_nc_cat=109&ccb=1-5&_nc_sid=09cbfe&_nc_eui2=AeGN2rwmOjNjGWahr5RXWqhrdm3hMT74ZwR2beExPvhnBJuUh5QpOHx6iWVFKxIB1x1p8JLiC29-vxGXjhOjzuai&_nc_ohc=RtUmgOaNylkAX8bl_fw&_nc_oc=AQkNDX7_8MblFpW2NAKoD71CfLV1sz20svupBC7vaiPZ2fsy9Q_jVqdoTQaW6-RUiHM&_nc_ht=scontent.ftnr2-1.fna&oh=00_AT_-YIPYNB2gZ2_N0f9kuBTcFjn9v_mFewYAsv9eIlhI3Q&oe=6250A166",
                                "subtitle" => "Miarahaba anao izahay, ireto ny tolotra misy ato",
                                "default_action" => [
                                    "type" => "web_url",
                                    "url" => "https://web.facebook.com/Namako-Bot-102269712007405",
                                    "webview_height_ratio" => "tall"
                                ],
                                "buttons" => [
                                    [
                                        "type" => "postback",
                                        "title" => "* Mp3",
                                        "payload" => json_encode(['TYPE' => 'info', 'data' => 'MP3'], JSON_THROW_ON_ERROR),
                                    ],
                                    [
                                        "type" => "postback",
                                        "title" => "* Mp4",
                                        "payload" => json_encode(['TYPE' => 'info', 'data' => 'MP4'], JSON_THROW_ON_ERROR),
                                    ]
                                ]
                            ],
                            [
                                "title" => "Fanampiana na hafatra",
                                "image_url" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSW0j76md33_zDTFY5EXrd_74izMi_7QNjKAg&usqp=CAU",
                                "subtitle" => "Raha mila fanampiana amin'ny fanambarana ny fichier maro maro na handefa hafatra",
                                "default_action" => [
                                    "type" => "web_url",
                                    "url" => "https://web.facebook.com/Namako-Bot-102269712007405",
                                    "webview_height_ratio" => "tall"
                                ],
                                "buttons" => [
                                    [
                                        "type" => "postback",
                                        "title" => "* Hafatra",
                                        "payload" => json_encode(['TYPE' => 'info', 'data' => 'MESSAGE'], JSON_THROW_ON_ERROR),
                                    ],
                                    [
                                        "type" => "postback",
                                        "title" => "* Fanampiana",
                                        "payload" => json_encode(['TYPE' => 'info', 'data' => 'HELP'], JSON_THROW_ON_ERROR),
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $this->sendRequestWithoutRetry($message);
    }

    /**
     * Send video in messenger media template
     *
     * @param int $id
     * @param string $url
     */
    public function sendMediaFacebookUrl(int $id, string $url): void
    {
        $message = [
            'recipient' => ['id' => $id],
            'message' => [
                "attachment" => [
                    "type" => "template",
                    "payload" => [
                        "template_type" => "media",
                        "elements" => [
                            [
                                "media_type" => "video",
                                "url" => $url
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $this->sendRequestWithoutRetry($message);
    }
}
