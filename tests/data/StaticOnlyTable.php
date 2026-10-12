<?php

namespace StaticAnalysisFixture;

use Cake\ORM\Table;

// PHPStan discovers this class through Composer's classmap without loading it at runtime.
class StaticOnlyTable extends Table
{
}
