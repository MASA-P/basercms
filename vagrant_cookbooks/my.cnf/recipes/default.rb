service "mysqld" do
  supports :status => true, :restart => true, :reload => true
  action [:restart]
end

template "/etc/my.cnf" do
  source "my.cnf.erb"
  owner "root"
  group "root"
  mode 0644
  notifies :restart, 'service[mysqld]'
end
