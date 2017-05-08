include apt

node default {

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
		'php7.1-xml'
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
		group	=> 'www-data',
	}
	
	# copy the laravel env file into a separate location
	exec { 'move .env':
		# puppet exec doesn't play nice with shell builtins, so we have to cheat
		command => '/bin/bash -c "cp /var/www/scb/.env.example /etc/laravel/.env"',
		creates	=> '/etc/laravel/.env',
		require => File['/etc/laravel']
	}
	
	# make sure the env file is editable by our vagrant user
	file { '/etc/laravel/.env':
		ensure	=> present,
		owner	=> 'vagrant',
		group	=> 'vagrant',
		mode	=> '0644'
	}
	
	# define the order of operations for installing and launching nginx
	Apt::Source['nginx']->Package['nginx-common']->Package['nginx']->File['/etc/nginx/nginx.conf']->Service['nginx']
}