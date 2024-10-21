<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Test\TestCase\Rule\Controller\Fake;

use Cake\Controller\Controller;

class FailingLoadComponentController extends Controller
{
    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Warning')->testWarning();
        $this->loadComponent('FormProtection');
        $this->loadComponent('CrazyWorld');
        $this->loadComponent('HelloWorld', [
            'className' => null,//when null should ignore and try with name argument. Must cause error
        ]);
        $this->loadComponent('LegacyFlash', [
            'className' => 'Flash',//Should use this instead of name argument. Not error
        ]);
        $this->loadComponent('Faker', [
            'className' => 'CrayFaker',//Should use this instead of name argument. Must cause error
        ]);
        $this->loadComponent('AppFaker', [
            'className' => 'Faker',//Should use this instead of name argument. Not error
        ]);
    }
}
