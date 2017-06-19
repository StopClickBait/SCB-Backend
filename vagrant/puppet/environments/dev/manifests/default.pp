include apt

node default {

	# install and configure postgresql
	class { 'postgresql::server': }
	
	postgresql::server::db { 'stopclickbait':
		user     => 'stopclickbait',
		password => postgresql_password('stopclickbait', 'UAFq2tQ07cVbvAz4'),
	}

	# prerequisites
	package { [
		'apt-transport-https',
		'vim',
		'curl',
		'git',
		'unzip'
	]:
		ensure=> installed
	}

	# add nginx repo
	apt::source { 'nginx':
		location => 'http://nginx.org/packages/debian/',
		repos    => 'nginx',
		key      => {
			'id'     => '573BFD6B3D8FBC641079A6ABABF5BD827BD9BF62',
			'server' => 'keyserver.ubuntu.com',
		},
		pin      => 900
	}
	
	# remove the default version of nginx if it's installed
	package { 'nginx-common':
			ensure => absent,
	}
	
	# ensure nginx is updated to the latest version
	package { 'nginx':
		ensure 	=> latest,
		require	=> [
			Apt::Source['nginx'],
			Class['apt::update']
		]
	}
	
	# make sure nginx is always running
	service { 'nginx':
		enable	=> true,
		ensure	=> running
	}
	
	# modify nginx configs
	file { '/etc/nginx/nginx.conf':
		ensure	=> file,
		source	=> '/vagrant/nginx/nginx.conf',
		notify	=> Service['nginx']
	}
	
	# add php7.1 repo
	apt::source { 'php7.1':
		location => 'https://packages.sury.org/php/',
		repos    => 'main',
		key      => {
			'id'     => 'DF3D585DB8F0EB658690A554AC0E47584A7A714D',
			'source' => 'https://packages.sury.org/php/apt.gpg',
		},
		require  => Package['apt-transport-https']
	}
	
	package { [
		'php7.1-fpm',
		'php7.1-mbstring',
		'php7.1-xml',
		'php7.1-pgsql'
	]:
		ensure	=> latest,
		require	=> [
			Apt::Source['php7.1'],
			Class['apt::update']
		]
	}
	
	# overwrite all of the php config files
	file { '/etc/php/7.1/fpm/php.ini':
		ensure	=> file,
		source	=> '/vagrant/php/fpm/php.ini',
		require	=> Package['php7.1-fpm'],
		notify	=> Service['php7.1-fpm']
	}
	
	file { '/etc/php/7.1/fpm/php-fpm.conf':
		ensure	=> file,
		source	=> '/vagrant/php/fpm/php-fpm.conf',
		require	=> Package['php7.1-fpm'],
		notify	=> Service['php7.1-fpm']
	}
	
	file { '/etc/php/7.1/cli/php.ini':
		ensure	=> file,
		source	=> '/vagrant/php/cli/php.ini',
		require	=> Package['php7.1-fpm']
	}
	
	file { '/etc/php/7.1/fpm/pool.d':
		ensure	=> directory,
		purge	=> true,
		force	=> true,
		recurse	=> true,
		owner	=> root,
		group	=> root,
		mode	=> "0644",
		source	=> "/vagrant/php/fpm/pool.d",
		require	=> Package['php7.1-fpm'],
		notify	=> Service['php7.1-fpm'],
	}
	
	service { 'php7.1-fpm':
		enable	=> true,
		ensure	=> running,
		require	=> [
			File['/etc/php/7.1/fpm/php-fpm.conf'],
			File['/etc/php/7.1/fpm/php.ini']
		]
	}
	
	# install composer if we haven't already
	exec { 'install composer':
		command => '/usr/bin/curl -sS https://getcomposer.org/installer | /usr/bin/php && /bin/mv composer.phar /usr/local/bin/composer',
		creates	=> '/usr/local/bin/composer',
		environment	=> 'HOME=/home/vagrant', # required by composer
		require => [
			Package['curl'],
			Package['php7.1-fpm']
		]
	}
	
	# the composer install above makes the vagrant user's composer directory be owned by root, so let's fix that
	file { '/home/vagrant/.composer':
		ensure	=> directory,
		owner	=> 'vagrant',
		group	=> 'vagrant',
		require	=> Exec['install composer']
	}
	
	# install app dependencies
	exec { 'install app dependencies':
		# puppet exec doesn't play nice with shell builtins, so we have to cheat
		command => '/bin/bash -c "cd /var/www/scb && /usr/local/bin/composer install"',
		environment	=> 'HOME=/home/vagrant', # required by composer
		creates	=> '/var/www/scb/backend/vendor',
		require => File['/home/vagrant/.composer']
	}
	
	# laravel keeps logs, configs, and caches with the code by default, so let's separate it out
	file { [
		'/var/laravel',
		'/etc/laravel'
	]: 
		ensure	=> directory,
		owner	=> 'www-data',
		group	=> 'vagrant',
		mode	=> '2775',
	}
	
	# laravel is too stupid to just create the directories it needs, so we have to do it instead
	file { [
		'/var/laravel/logs',
		'/var/laravel/app',
		'/var/laravel/framework'
	]: 
		ensure	=> directory,
		owner	=> 'www-data',
		group	=> 'vagrant',
		mode	=> '2775',
		require => File['/var/laravel']
	}
	
	file { [
		'/var/laravel/framework/cache',
		'/var/laravel/framework/sessions',
		'/var/laravel/framework/testing',
		'/var/laravel/framework/views',
	]: 
		ensure	=> directory,
		owner	=> 'www-data',
		group	=> 'vagrant',
		mode	=> '2775',
		require => File['/var/laravel/framework']
	}
	
	file { [
		'/var/laravel/app/public'
	]: 
		ensure	=> directory,
		owner	=> 'www-data',
		group	=> 'vagrant',
		mode	=> '2775',
		require => File['/var/laravel/app']
	}
	
	# copy the laravel env file into a separate location
	file { '/etc/laravel/.env':
		ensure	=> present,
		source	=> '/vagrant/laravel/dev.env',
		require => File['/etc/laravel']
	}
	
	# set up the database
	exec { 'artisan migrate:install':
		# sets up the migration table and creates a marker file to prevent re-running it
		command => '/usr/bin/php /var/www/scb/artisan migrate:install && touch /var/laravel/migrate-installed',
		creates	=> '/var/laravel/migrate-installed', 
		require	=> [
			File['/var/laravel'],
			Exec['install app dependencies'],
			Postgresql::Server::Db['stopclickbait']
		]
	}
	
	exec { 'artisan migrate':
		# applies defined migrations to make db ready for use
		command => '/usr/bin/php /var/www/scb/artisan migrate',
		require	=> Exec['artisan migrate:install']
	}
	
	# clean out the caches
	exec { 'artisan cache:clear':
		command => '/usr/bin/php /var/www/scb/artisan cache:clear',
		require	=> [
			File['/var/laravel/framework/cache'],
			Exec['install app dependencies']
		]
	}
	
	exec { 'artisan view:clear':
		command => '/usr/bin/php /var/www/scb/artisan view:clear',
		require	=> [
			File['/var/laravel/framework/views'],
			Exec['install app dependencies']
		]
	}
	
	exec { 'artisan config:clear':
		command => '/usr/bin/php /var/www/scb/artisan config:clear',
		require	=> [
			File['/var/laravel/framework/cache'],
			Exec['install app dependencies']
		]
	}
	
	# define the order of operations for installing and launching nginx
	Apt::Source['nginx']->Package['nginx-common']->Package['nginx']->File['/etc/nginx/nginx.conf']->Service['nginx']
}