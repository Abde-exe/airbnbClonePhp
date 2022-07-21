<?php

namespace App\Data;

class SearchData
{

    /**
     * page 1 de pagination
     * @var int
     */
    public $page = 1;
    /**
     * mots clés
     * @var string
     */
    public $q = ' ';

    /**
     * categories
     * @var null|Category[]
     */
    public $categories = [];

    /**
     * prixJournalier
     * @var null|integer
     */
    public $max;
    /**
     * prixJournalier
     * @var null|integer
     */
    public $min;
}
