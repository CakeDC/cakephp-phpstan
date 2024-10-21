# CakePHP extension for PHPStan

![Build Status](https://github.com/CakeDC/cakephp-phpstan/actions/workflows/ci.yml/badge.svg)
[![Downloads](https://poser.pugx.org/CakeDC/cakephp-phpstan/d/total.png)](https://packagist.org/packages/CakeDC/cakephp-phpstan)
[![Latest Version](https://poser.pugx.org/CakeDC/cakephp-phpstan/v/stable.png)](https://packagist.org/packages/CakeDC/cakephp-phpstan)
[![License](https://poser.pugx.org/CakeDC/cakephp-phpstan/license.svg)](LICENSE.txt)

* [PHPStan](https://phpstan.org/)
* [CakePHP](https://cakephp.org/)

Provide services and rules for a better PHPStan analyze on CakePHP applications, includes services to resolve types (Table, Helpers, Behaviors, etc)
and multiple rules.

# Installation

To use this extension, require it through [Composer](https://getcomposer.org/):

```
composer require --dev cakedc/cakephp-phpstan
```


If you also install [phpstan/extension-installer](https://github.com/phpstan/extension-installer), then you're all set!

<details>
    <summary>Manual installation</summary>

If you don't want to use `phpstan/extension-installer`, include `extension.neon` in your project's PHPStan config:
```
includes:
    - vendor/cakedc/cakephp-phpstan/extension.neon
```

</details>


# General class load|fetch extensions
Features included:
1. Provide correct return type for `Cake\ORM\Locator\LocatorInterface::get()`
1. Provide correct return type for `Cake\Controller\Controller::loadComponent()`
1. Provide correct return type for `Cake\Controller\Controller::fetchTable()`
1. Provide correct return type for `Cake\Controller\Component::fetchTable()`
1. Provide correct return type for `Cake\Command\Command::fetchTable()`
1. Provide correct return type for `Cake\Mailer\Mailer::fetchTable()`
1. Provide correct return type for `Cake\View\Cell::fetchTable()`
1. Provide correct return type for `Cake\Console\ConsoleIo::helper()`

# Table class return type extensions
### TableEntityDynamicReturnTypeExtension
1. Provide correct return type for `Cake\ORM\Table::get` based on your table class name
1. Provide correct return type for `Cake\ORM\Table::newEntity` based on your table class name
1. Provide correct return type for `Cake\ORM\Table::newEntities` based on your table class name
1. Provide correct return type for `Cake\ORM\Table::newEmptyEntity` based on your table class name
1. Provide correct return type for `Cake\ORM\Table::findOrCreate` based on your table class name

<details>
      <summary>Examples:</summary>

```php
  //Now PHPStan know that \App\Models\Table\NotesTable::get returns \App\Model\Entity\Note
  $note = $this->Notes->get(1);
  $note->note = 'My new note';//No error

  //Now PHPStan know that \App\Models\Table\NotesTable::newEntity returns \App\Model\Entity\Note
  $note = $this->Notes->newEntity($data);
  $note->note = 'My new note new entity';//No error

  //Now PHPStan know that \App\Models\Table\NotesTable::newEmptyEntity returns \App\Model\Entity\Note
  $note = $this->Notes->newEmptyEntity($data);
  $note->note = 'My new note new empty entity';//No error

   //Now PHPStan know that \App\Models\Table\NotesTable::findOrCreate returns \App\Model\Entity\Note
  $note = $this->Notes->findOrCreate($data);
  $note->note = 'My entity found or created';//No error

  //Now PHPStan know that \App\Models\Table\NotesTable::newEntities returns \App\Model\Entity\Note[]
  $notes = $this->Notes->newEntities($data);
  foreach ($notes as $note) {
    $note->note = 'My new note';//No error
  }
```
</details>

### TableFirstArgIsTheReturnTypeExtension
1. Provide correct return type for `Cake\ORM\Table::patchEntity` based on the first argument passed
1. Provide correct return type for `Cake\ORM\Table::patchEntities` based on the first argument passed
1. Provide correct return type for `Cake\ORM\Table::save` based on the first argument passed
1. Provide correct return type for `Cake\ORM\Table::saveOrFail` based on the first argument passed
1. Provide correct return type for `Cake\ORM\Table::saveMany` based on the first argument passed
1. Provide correct return type for `Cake\ORM\Table::saveManyOrFail` based on the first argument passed
1. Provide correct return type for `Cake\ORM\Table::deleteMany` based on the first argument passed
1. Provide correct return type for `Cake\ORM\Table::deleteManyOrFail` based on the first argument passed
1. Provide correct return type for `Cake\ORM\Locator\LocatorAwareTrait::fetchTable` based on the first argument passed
1. Provide correct return type for `Cake\Mailer\MailerAwareTrait::getMailer` based on the first argument passed

<details>
      <summary>Examples:</summary>

```php
  //Now PHPStan know that \App\Models\Table\NotesTable::get returns \App\Model\Entity\Note
  $note = $this->Notes->get(1);
  $notes = $this->Notes->newEntities($data);

  //Since PHPStan knows the type of $note, these methods call use the same type as return type:
  $note = $this->Notes->patchEntity($note, $data);
  $text = $note->note;//No error.

  $note = $this->Notes->save($note);
  $text = $note->note;//No error.

  $note = $this->Notes->saveOrFail($note);
  $text = $note->note;//No error.
  //Since PHPStan knows the type of $notes, these methods call use the same type as return type:
  $notes = $this->Notes->patchEntities($notes);
  $notes = $this->Notes->saveMany($notes);
  $notes = $this->Notes->saveManyOrFail($notes);
  $notes = $this->Notes->deleteMany($notes);
  $notes = $this->Notes->deleteManyOrFail($notes);
```
</details>

# Rules
All rules provided by this library are included in [rules.neon](rules.neon) and are enabled by default:

### AddAssociationExistsTableClassRule
This rule check if the target association has a valid table class when calling to Table::belongsTo,
Table::hasMany, Table::belongsToMany, Table::hasOne and AssociationCollection::load.

### AddAssociationMatchOptionsTypesRule
This rule check if association options are valid option types based on what each class expects. This cover calls to Table::belongsTo,
Table::hasMany, Table::belongsToMany, Table::hasOne and AssociationCollection::load.

### AddBehaviorExistsClassRule
This rule check if the target behavior has a valid class when calling to Table::addBehavior and BehaviorRegistry::load.

### DisallowEntityArrayAccessRule
This rule disallow array access to entity in favor of object notation, is easier to detect a wrong property and to refactor code.

### GetMailerExistsClassRule
This rule check if the target mailer is a valid class when calling to Cake\Mailer\MailerAwareTrait::getMailer.

### LoadComponentExistsClassRule
This rule check if the target component has a valid class when calling to Controller::loadComponent and ComponentRegistry::load.

### OrmSelectQueryFindMatchOptionsTypesRule
This rule check if the options (args) passed to Table::find and SelectQuery are valid find options types.

### TableGetMatchOptionsTypesRule
This rule check if the options (args) passed to Table::get are valid find options types.

To enable this rule update your phpstan.neon with:

```
parameters:
	cakeDC:
	 	disallowEntityArrayAccessRule: true
```

### How to disable a rule
Each rule has a parameter in cakeDC 'namespace' to enable or disable, it is the same name of the
rule with first letter in lowercase.
For example to disable the rule AddAssociationExistsTableClassRule you should have
```
parameters:
	cakeDC:
	 	addAssociationExistsTableClassRule: false
```

# PHPDoc Extensions
### TableAssociationTypeNodeResolverExtension
Fix intersection association phpDoc to correct generic object type, ex:

Change `\Cake\ORM\Association\BelongsTo&\App\Model\Table\UsersTable` to `\Cake\ORM\Association\BelongsTo<\App\Model\Table\UsersTable>`


### Tips
To make your life easier make sure to have `@mixin` and `@method` annotations in your table classes.
The `@mixin` annotation will help phpstan know you are using methods from behavior, and `@method` annotations
will allow it to know the correct return types for methods like `Table::get()`, `Table::newEntity()`.

You can easily update annotations with the plugin [IdeHelper](https://github.com/dereuromark/cakephp-ide-helper).

Support
-------

For bugs and feature requests, please use the [issues](https://github.com/CakeDC/cakephp-phpstan/issues) section of this repository.

Commercial support is also available, [contact us](https://www.cakedc.com/contact) for more information.

Contributing
------------

If you'd like to contribute new features, enhancements or bug fixes to the plugin, please read our [Contribution Guidelines](https://www.cakedc.com/contribution-guidelines) for detailed instructions.
