#  CakePHP DatabaseLog Plugin

[![Build Status](https://api.travis-ci.org/dereuromark/CakePHP-DatabaseLog.png)](https://travis-ci.org/dereuromark/CakePHP-DatabaseLog)
[![Coverage Status](https://img.shields.io/codecov/c/github/dereuromark/CakePHP-DatabaseLog/master.svg)](https://codecov.io/github/dereuromark/CakePHP-DatabaseLog?branch=master)
[![Latest Stable Version](https://poser.pugx.org/dereuromark/CakePHP-DatabaseLog/v/stable.svg)](https://packagist.org/packages/dereuromark/CakePHP-DatabaseLog)
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%205.5-8892BF.svg)](https://php.net/)
[![License](https://poser.pugx.org/dereuromark/CakePHP-DatabaseLog/license.png)](https://packagist.org/packages/dereuromark/CakePHP-DatabaseLog)
[![Total Downloads](https://poser.pugx.org/dereuromark/CakePHP-DatabaseLog/d/total.png)](https://packagist.org/packages/dereuromark/CakePHP-DatabaseLog)

DatabaseLog engine for CakePHP applications.

**This branch is for CakePHP 3.x**

## Features

- Easy setup.
- Defaults to SQLite as single app application light weight approach.
- Ideal for multi app applications where logging to a file is just not convenient.
- Fallback to SQLite in case the DB is not reachable.
- Simple admin interface to view/delete logs included.

## Install

### Composer (preferred)
```
composer require dereuromark/cakephp-databaselog
```

## Setup
Enable the plugin in your `config/bootstrap.php` or call
```
bin/cake plugin load DatabaseLog
```

You can simply modify the existing config entries in your `config/app.php`:
 ```php
	'Log' => [
		'debug' => [
			'className' => 'DatabaseLog.Database'
		],
		'error' => [
			'className' => 'DatabaseLog.Database'
		],
	],
```
This will use the `database_log` connection and an SQLite file database by default, stored in your `logs` folder.

### Using an actual database (optional)
Create a config setting in your `config/app.php` what database connection it should log to:
```php
'DatabaseLog' => [
	'datasource' => 'my_datasource', // DataSource to use
]
```
It is recommended to not use the same datasource as your production server (`default`) because when the DB is not reachable logging to it will
also not be possible. In that case it will fallback to SQLite file logging on this server instance, though.

Once the datasource is reachable and once the first log entry is being written, the database table (defaulting to `database_logs`) will be automatically
created. No need to manually run any migration or SQL script here.

## Usage

Anywhere in your app where you call log() or CakeLog::write the DatabaseLog engine will be used.
```php
$this->log('This is a detailed message logged to the database', 'error');
// or
Log::write('error', 'This is a detailed message logged to the database');
```

Navigate to `http://www.example.com/admin/database-log/logs` to view/search/delete your logs.

You can customize the template with a custom theme if necessary.
