# SSH

- [Installation / インストール](#installation)
- [Configuration / 設定](#configuration)
- [Basic Usage / 基本的な使い方](#basic-usage)
- [Tasks / タスクの使い方](#tasks)
- [SFTP Downloads / SFTP ダウンロード](#sftp-downloads)
- [SFTP Uploads / SFTP アップロード](#sftp-uploads)
- [Tailing Remote Logs / リモートログ監視](#tailing-remote-logs)

<a name="installation"></a>
## Installation

> プロジェクトの名前空間を 'App' として記述していますが、 'MyProject' のように名前空間を変更している場合は読み替えてください

はじめに Composer を利用してこのパッケージをインストールします。  
プロジェクトの`composer.json` のrequireに`laravelcollective/remote`を追記してください

```
    "require": {
        "laravelcollective/remote": "~5.0"
    }
```

次に、コンソールから 次のComposerコマンド を実行します:

```  
$ composer update
```

次に、`config/app.php` 内の `providers`に下記の新しいProviderを追加します:

```php
  'providers' => [
    // ...
    'Collective\Remote\RemoteServiceProvider',
    // ...
  ],
```

最後に、`config/app.php` 内の `aliases`に以下のように追記します:

```php
  'aliases' => [
    // ...
      'SSH' => 'Collective\Remote\RemoteFacade',
    // ...
  ],
```
<a name="configuration"></a>
## Configuration

このパッケージは、リモートサーバで簡単にコマンドが実行できるように機能が提供されており、  
開発者はリモートサーバで実行するArtisanコマンドを簡単に実装することができます。

The configuration file is located at `config/remote.php`, and contains all of the options you need to configure your remote connections. The `connections` array contains a list of your servers keyed by name. Simply populate the credentials in the `connections` array and you will be ready to start running remote tasks. Note that the `SSH` can authenticate using either a password or an SSH key.

> **Note:** Need to easily run a variety of tasks on your remote server? Check out the [Envoy task runner](http://laravel.com/docs/5.0/envoy)!

<a name="basic-usage"></a>
## Basic Usage

#### Running Commands On The Default Server

To run commands on your `default` remote connection, use the `SSH::run` method:

	SSH::run([
		'cd /var/www',
		'git pull origin master',
	]);

#### Running Commands On A Specific Connection

Alternatively, you may run commands on a specific connection using the `into` method:

	SSH::into('staging')->run([
		'cd /var/www',
		'git pull origin master',
	]);

#### Catching Output From Commands

You may catch the "live" output of your remote commands by passing a Closure into the `run` method:

	SSH::run($commands, function($line)
	{
		echo $line.PHP_EOL;
	});

## Tasks
<a name="tasks"></a>

If you need to define a group of commands that should always be run together, you may use the `define` method to define a `task`:

	SSH::into('staging')->define('deploy', [
		'cd /var/www',
		'git pull origin master',
		'php artisan migrate',
	]);

Once the task has been defined, you may use the `task` method to run it:

	SSH::into('staging')->task('deploy', function($line)
	{
		echo $line.PHP_EOL;
	});

<a name="sftp-downloads"></a>
## SFTP Downloads

The `SSH` class includes a simple way to download files using the `get` and `getString` methods:

	SSH::into('staging')->get($remotePath, $localPath);

	$contents = SSH::into('staging')->getString($remotePath);

<a name="sftp-uploads"></a>
## SFTP Uploads

The `SSH` class also includes a simple way to upload files, or even strings, to the server using the `put` and `putString` methods:

	SSH::into('staging')->put($localFile, $remotePath);

	SSH::into('staging')->putString($remotePath, 'Foo');

<a name="tailing-remote-logs"></a>
## Tailing Remote Logs

Laravel includes a helpful command for tailing the `laravel.log` files on any of your remote connections. Simply use the `tail` Artisan command and specify the name of the remote connection you would like to tail:

	php artisan tail staging

	php artisan tail staging --path=/path/to/log.file
