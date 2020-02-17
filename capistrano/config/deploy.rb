lock '3.10.2'

set :application, 'stampapp'
set :repo_url, 'git@github.com:Sztig/inzynierka.git'

set :keep_releases, 2

set :linked_dirs, fetch(:linked_dirs, []).push('public/uploads')
