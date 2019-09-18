@include('envoy.config.php');

@servers(['gaukmotors' => $ssh['gaukmotors']['server'], 'gaukmotorsLiveUk' => $ssh['gaukmotorsLiveUk']['server']])

@setup
$now = new DateTime();
$date = $now->format('YmdHis');
$env = isset($env) ? $env : "local";
$branch = isset($branch) ? $branch : "master";
$server = isset($server) ? $server : "gaukmotors";
@endsetup

@task('test', ['on' => $server])
echo "Server: {{$server}}";
ls -la
@endtask

@task('initdev', ['on' => $server])
rm -fr {{ $ssh[$server]['path'] }};
rm -fr {{ $ssh[$server]['publicPath'] }};
echo "Setting up directories";
mkdir {{ $ssh[$server]['path'] }};
mkdir {{ $ssh[$server]['path'] }}/releases;
cd {{ $ssh[$server]['path'] }}/releases;
/usr/local/cpanel/3rdparty/bin/git clone {{ $repo }} --branch={{ $branch }} --depth=1 current;
echo "Repository cloned";
chmod -R 0777 current/storage;
chmod -R 0777 current/bootstrap/cache;
mkdir {{ $ssh[$server]['publicPath'] }};
cp -R current/public/* {{ $ssh[$server]['publicPath'] }}/;
rm -fr {{ $ssh[$server]['publicPath'] }}/index.php;
mv {{ $ssh[$server]['publicPath'] }}/index.{{ $server }}.php {{ $ssh[$server]['publicPath'] }}/index.php
mv {{ $ssh[$server]['publicPath'] }}/filemanager/connectors/php/{{ $server }}.config.php {{ $ssh[$server]['publicPath'] }}/filemanager/connectors/php/default.config.php
yes | cp -a current/public/.htaccess {{ $ssh[$server]['publicPath'] }}/.htaccess
chmod -R 0777 {{ $ssh[$server]['publicPath'] }}/images/user;
chmod -R 0777 {{ $ssh[$server]['publicPath'] }}/filemanager/userfiles;
echo "Public Files Moved";
touch {{ $ssh[$server]['path'] }}/.env;
ln -s {{ $ssh[$server]['path'] }}/.env current/.env;
echo "Environment file created";
cd current;
/home/{{ $server }}/composer.phar install;
cd ..;
ln -s {{ $ssh[$server]['path'] }}/releases/current {{ $ssh[$server]['path'] }}/current;
echo "Initial deployment complete";
echo "once the env file has been modified please run";
echo "cd {{ $ssh[$server]['path'] }}/current && php artisan migrate --env=local --force --no-interaction";
@endtask

@task('deploydev', ['on' => 'gaukmotors'])
cd {{ $ssh['gaukmotors']['path'] }}/releases;
rm -fr current_old
/usr/local/cpanel/3rdparty/bin/git clone {{ $repo }} --branch={{ $branch }} --depth=1 currentNew;
echo "Repository cloned";
ln -s {{ $ssh['gaukmotors']['path'] }}/.env currentNew/.env;
echo "Env Linked";
chmod -R 0777 currentNew/storage;
chmod -R 0777 currentNew/bootstrap/cache;
cp -fr current/storage/* currentNew/storage/
chmod -R 0777 currentNew/storage;
cp -fr current/resources/views/emails/* currentNew/resources/views/emails/
chmod -R 0777 currentNew/resources/views/emails;
echo "Storage permissions set";
yes | cp -R currentNew/public/* {{ $ssh['gaukmotors']['publicPath'] }}/;
rm -fr {{ $ssh['gaukmotors']['publicPath'] }}/index.php;
rm -fr {{ $ssh['gaukmotors']['publicPath'] }}/index.gaukmotors.php;
rm -fr {{ $ssh['gaukmotors']['publicPath'] }}/index.gaukmotorsLiveUk.php;
mv {{ $ssh['gaukmotors']['publicPath'] }}/index.gaukmotorsDev.php {{ $ssh['gaukmotors']['publicPath'] }}/index.php
mv {{ $ssh['gaukmotors']['path'] }}/releases/currentNew/config/gaukDev-lfm.php {{ $ssh['gaukmotors']['path'] }}/releases/currentNew/config/lfm.php
echo "Public DIR copied";
cd currentNew
/home/gaukmotors/composer.phar install --no-interaction --no-dev;
php artisan migrate --env=local --force --no-interaction
cd ..
mv current current_old
mv currentNew current
cd current
php artisan quantum:install
php artisan route:cache
php artisan queue:restart
echo "Deployment complete";
@endtask

@task('deployliveuk', ['on' => 'gaukmotorsLiveUk'])
cd {{ $ssh['gaukmotorsLiveUk']['path'] }}/releases;
rm -fr current_old
/usr/local/cpanel/3rdparty/bin/git clone {{ $repo }} --branch={{ $branch }} --depth=1 currentNew;
echo "Repository cloned";
ln -s {{ $ssh['gaukmotorsLiveUk']['path'] }}/.env currentNew/.env;
echo "Env Linked";
chmod -R 0777 currentNew/storage;
chmod -R 0777 currentNew/bootstrap/cache;
cp -fr current/storage/* currentNew/storage/
chmod -R 0777 currentNew/storage;
cp -fr current/resources/views/emails/* currentNew/resources/views/emails/
chmod -R 0777 currentNew/resources/views/emails;
echo "Storage permissions set";
yes | cp -R currentNew/public/* {{ $ssh['gaukmotorsLiveUk']['publicPath'] }}/;
rm -fr {{ $ssh['gaukmotorsLiveUk']['publicPath'] }}/index.php;
rm -fr {{ $ssh['gaukmotorsLiveUk']['publicPath'] }}/index.gaukmotors.php;
rm -fr {{ $ssh['gaukmotorsLiveUk']['publicPath'] }}/index.gaukmotorsDev.php;
mv {{ $ssh['gaukmotorsLiveUk']['publicPath'] }}/index.gaukmotorsLiveUk.php {{ $ssh['gaukmotorsLiveUk']['publicPath'] }}/index.php
mv {{ $ssh['gaukmotorsLiveUk']['path'] }}/releases/currentNew/config/gauk-lfm.php {{ $ssh['gaukmotorsLiveUk']['path'] }}/releases/currentNew/config/lfm.php
echo "Public DIR copied";
cd currentNew
cd storage/framework/cache
rm -fr data
mkdir data
cd ../../../
/home/ukgaukmotors/composer.phar install --no-interaction --no-dev;
php artisan migrate --env=local --force --no-interaction
cd ..
mv current current_old
mv currentNew current
cd current
php artisan quantum:install
php artisan route:cache
php artisan cache:clear
php artisan view:clear
php artisan queue:restart
echo "Deployment complete";
@endtask