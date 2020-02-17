set :branch, 'master'

server 'vps790232.ovh.net', user: 'sztig', roles: %w{app}

set :deploy_env, 'latest'
set :deploy_to, "/var/www/stampapp/production"

namespace :deploy do
  before :updated, :symfony_maintenance do
    on roles(:app), in: :groups, limit: 3, wait: 10 do
        within "#{release_path}/" do
          execute 'composer', 'install', '--ignore-platform-reqs'
          execute 'yarn', 'run', 'encore', 'production'
        end
    end
  end
end

server 'vps790232.ovh.net',
  user: 'sztig',
  roles: %w{app},
  ssh_options: {
    host_name: 'vps790232.ovh.net',
    user: 'sztig', # overrides user setting above
    keys: %w(~/.ssh/id_rsa),
    forward_agent: true,
    auth_methods: %w(publickey password)
  }