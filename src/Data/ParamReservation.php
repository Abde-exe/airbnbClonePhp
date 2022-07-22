<?php

namespace App\Data;

use DateTime;
use Symfony\Component\Validator\Constraints\Date;

class ParamReservation
{



    /**
     * date entrée
     * @var string A "Y-m-d" formatted value
     */
    public $dateE;
    /**
     * date sortie
     * * @var string A "Y-m-d" formatted value

     */
    public $dateS;

    /**
     * nbPers
     * @var integer
     */
    public $nbPers = 1;
}
