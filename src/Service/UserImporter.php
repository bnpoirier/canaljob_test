<?php
/**
 * Created by PhpStorm.
 * User: Brendan
 * Date: 13/06/2018
 * Time: 18:30
 */

namespace App\Service;


use App\Entity\Profile;
use App\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserImporter
{
    protected $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param UploadedFile $csv
     * @return array
     * @throws \Exception
     */
    public function getUsersFromCSV(UploadedFile $csv)
    {
        if (($handle = fopen($csv->getRealPath(), "r")) == FALSE) {
            throw new \Exception("Unable to open the CSV file");
        }

        $users = [];

        // We skip the first header line
        fgetcsv($handle);
        while(($data = fgetcsv($handle, 0 , ";")) !== FALSE)
        {
            $users[] = $this->createUser($data);
        }

        return $users;
    }

    public function createUser($data){
        $user = new User();
        $profile = new Profile();
        $user->setProfile($profile);

        $user->setEmail(@$data[2]);
        $user->setPassword($this->passwordEncoder->encodePassword($user, @$data[0]));

        $profile->setFirstname(@$data[0]);
        $profile->setLastname(@$data[1]);
        $profile->setAddress(@$data[3]);

        return $user;
    }
}