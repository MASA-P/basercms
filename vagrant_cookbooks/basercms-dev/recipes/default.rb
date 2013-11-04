execute "yum" do
  command "yum -y update"
end

packages = %w{vim-enhanced git httpd httpd-devel mysql-server curl gd postfix dovecot php php-cli php-pecl-apc php-pecl-xdebug php-pear php-pdo php-mysql php-sqlite php-curl php-gd php-mbstring php-xml php-xmlrpc phpMyAdmin}
packages.each do |packagename|
  package packagename do
    action [:install, :upgrade]
  end
end

execute "phpunit" do
  command "pear config-set auto_discover 1; pear install pear.phpunit.de/PHPUnit"
  not_if { File.exists?("/usr/bin/phpunit") }
end

template "/etc/httpd/conf.d/vhosts.conf" do
  mode 0644
  source "vhosts.conf.erb"
end

#このへんにiptablesに80ポートを空ける設定を入れる

services = %w{mysqld httpd dovecot postfix}
services.each do |servicename|
    service servicename do
      action [:start, :restart]
    end
end

execute "chmod-tmp" do
  command "chmod -Rf 0777 /vagrant_data/app/tmp"
end
