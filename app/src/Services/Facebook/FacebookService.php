<?php
/**
 * @author <Akartis>
 */

namespace App\Services\Facebook;


use App\Entity\Customer;
use App\Entity\ErrorDetail;
use App\Entity\Models\FacebookProfileInfo;
use App\ObjectManager\EntityObjectManager;
use App\Services\Application\AppService;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Request;

class FacebookService
{
    private string $fbToken;

    public function __construct(
        private ClientInterface $client,
        private AppService $appService,
        private EntityObjectManager $em
    )
    {
        $this->fbToken = $_ENV['FACEBOOK_TOKEN'];
    }

    /**
     * Get facebook profile info with id
     *
     * @return FacebookProfileInfo
     * @throws GuzzleException
     */
    public function getUserInfo(string|int $id): Customer|null
    {
        $url = $_ENV['FB_PERSONAL_INFO'];
        $link = sprintf("%s/%s?access_token=%s", $url, $id, $this->fbToken);

        try {
            $result = (string)$this->client->request(Request::METHOD_GET, $link)->getBody();
            $resultArray = json_decode($result, true, 512, JSON_THROW_ON_ERROR);

            return (new Customer())
                ->setFacebookId($resultArray['id'])
                ->setFirstName($resultArray['first_name'])
                ->setLastName($resultArray['last_name'])
                ->setProfilePic($resultArray['profile_pic']);

        }catch (\Exception $e){
            $errorDetail = (new ErrorDetail())->setDetail($e->getMessage())
                ->setOrigin(sprintf("Get user info with id %s", $id))
                ->setSearchTerm($this->appService->getSearchTerm());
            $this->em->save($errorDetail);

            return null;
        }

    }
}
