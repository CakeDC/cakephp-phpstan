<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\Locator\LocatorAwareTrait;

class TableAwareComponent extends Component
{
    use LocatorAwareTrait;
}
