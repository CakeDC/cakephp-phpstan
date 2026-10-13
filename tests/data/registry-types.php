<?php

namespace CakeDC\PHPStan\Test\Data;

use Cake\Command\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleIoInterface;
use App\Controller\Component\TableAwareComponent;
use Cake\Controller\Controller;
use Cake\Mailer\Mailer;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Locator\LocatorInterface;
use Cake\ORM\Table;
use Cake\View\Cell;
use StaticAnalysisFixture\StaticOnlyTable;

use function PHPStan\Testing\assertType;

function registryTypes(
    LocatorInterface $locator,
    Controller $controller,
    TableAwareComponent $component,
    Command $command,
    Mailer $mailer,
    Cell $cell,
    ConsoleIo $io,
    ConsoleIoInterface $interfaceIo,
    HasMany $association,
    Table $table,
    string $unknownName,
    bool $choose
): void {
    assertType('App\\Model\\Table\\VeryCustomize00009ArticlesTable', $locator->get('VeryCustomize00009Articles'));
    assertType(
        'CakeDC\\MyPlugin\\Model\\Table\\HelloWorld101010ArticlesTable',
        $locator->get('CakeDC/MyPlugin.HelloWorld101010Articles')
    );
    assertType('Cake\\ORM\\Table', $locator->get($unknownName));
    assertType('Cake\\ORM\\Table', $locator->get('MissingTable'));
    assertType(StaticOnlyTable::class, $locator->get(StaticOnlyTable::class));
    assertType(
        'App\\Model\\Table\\VeryCustomize00009ArticlesTable',
        $controller->fetchTable('VeryCustomize00009Articles')
    );
    assertType(
        'App\\Model\\Table\\VeryCustomize00009ArticlesTable',
        $component->fetchTable('VeryCustomize00009Articles')
    );
    assertType(
        'App\\Model\\Table\\VeryCustomize00009ArticlesTable',
        $command->fetchTable('VeryCustomize00009Articles')
    );
    assertType('App\\Model\\Table\\VeryCustomize00009ArticlesTable', $mailer->fetchTable('VeryCustomize00009Articles'));
    assertType('App\\Model\\Table\\VeryCustomize00009ArticlesTable', $cell->fetchTable('VeryCustomize00009Articles'));
    assertType('Cake\\ORM\\Table', $controller->fetchTable());
    assertType('Cake\\ORM\\Table', $controller->fetchTable(null));
    assertType('Cake\\ORM\\Table', $controller->fetchTable($choose ? 'VeryCustomize00009Articles' : null));
    assertType('Cake\\Controller\\Component\\FlashComponent', $controller->loadComponent('Flash'));
    assertType('Cake\\Controller\\Component', $controller->loadComponent($unknownName));
    assertType('Cake\\Console\\Helper\\ProgressHelper', $io->helper('progress'));
    assertType('App\\Console\\Helper\\MyHeadingHelper', $interfaceIo->helper('MyHeading'));
    assertType('App\\Console\\Helper\\BazBazHelper', $io->helper('BazBaz'));
    assertType('Cake\\Console\\Helper', $io->helper($unknownName));
    assertType(
        'App\\Console\\Helper\\BazBazHelper|App\\Console\\Helper\\MyHeadingHelper',
        $io->helper($choose ? 'BazBaz' : 'MyHeading')
    );
    assertType('Cake\\ORM\\Query\\SelectQuery', $table->findByTitle('sample'));
    assertType('Cake\\ORM\\Query\\SelectQuery', (new StaticOnlyTable())->findByTitle('sample'));
    assertType('bool', $association->exists(['id' => 1]));
}
