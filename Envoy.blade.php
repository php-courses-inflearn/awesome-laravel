@servers(['homestead' => 'vagrant@homestead'])

@setup
    $repo = 'https://github.com/php-courses-inflearn/laravel.git';

    $releases = '~/releases';
    $app = '~/app';

    $shared = [
        '~/storage' => 'storage'
    ];
@endsetup

@task('deploy', ['on' => ['homestead']])
    # Make 'releases' Directory
    mkdir -p {{ $releases }};

    # Git
    git clone -b {{ $tag }} {{ $repo }} {{ "$releases/$tag" }}
    cd {{ "$releases/$tag" }}

    # Create 'shared' symlinks
    @foreach ($shared as $global => $local)
        if [ ! -d {{ $global }} ]; then
            cp -r {{ $local }} {{ $global }};
        fi;

        [ -d {{ $local }} ] && rm -rf {{ $local }};
        ln -nfs {{ $global }} {{ $local }};
    @endforeach

    # Install Dependencies
    composer install --optimize-autoloader --no-interaction --no-dev

    # Create Storage Links
    php artisan storage:link

    # Decrypt .env.production
    export LARAVEL_ENV_ENCRYPTION_KEY={{ $key }}
    export APP_ENV=production

    php artisan env:decrypt --env=production

    # Install Node.js Dependencies
    npm ci
    npm run build

    # Database Migration
    php artisan migrate --no-interaction --force

    # Optimize
    # @see https://laravel.com/docs/9.x/deployment#optimization
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

    # Create a Symlink
    ln -nfs {{ "$releases/$tag" }} {{ $app }}
@endtask
