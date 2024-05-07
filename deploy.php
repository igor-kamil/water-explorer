<?php
namespace Deployer;

require 'recipe/laravel.php';
require 'contrib/npm.php';

// Project repository
set('repository', 'git@github.com:igor-kamil/water-explorer.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts
host('water-explorer.mkg-hamburg.de')
    ->set('remote_user', 'ploi')
    ->set('deploy_path', '~/water-explorer.mkg-hamburg.de');

// Tasks

task('build', function () {
    cd('{{release_path}}');
    run('npm ci');
    run('npm run build');
});

// Hooks

after('deploy:vendors', 'build');
after('deploy:failed', 'deploy:unlock');
