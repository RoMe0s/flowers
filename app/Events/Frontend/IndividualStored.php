<?php

namespace App\Events\Frontend;

use App\Events\Event;
use App\Models\Individual;
use Illuminate\Queue\SerializesModels;

class IndividualStored extends Event
{
    use SerializesModels;

    public $individual;

    /**
     * IndividualStored constructor.
     * @param Individual $individual
     */
    public function __construct(Individual $individual)
    {
        $this->individual = $individual;
    }
}
