lock '3.10.0'

set :application, 'stampapp'
set :repo_url, 'git@github.com:Sztig/inzynierka.git'

set :keep_releases, 2

set :linked_dirs, fetch(:linked_dirs, []).push('public/uploads')

before 'deploy:updated', 'symfony:doctrine:cache:clear_metadata'
before 'deploy:updated', 'symfony:doctrine:cache:clear_query'
before 'deploy:updated', 'symfony:doctrine:cache:clear_result'
after  'deploy:updated', 'symfony:doctrine:migrations'