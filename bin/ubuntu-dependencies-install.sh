apt-get install build-essential webp pngquant optipng pngcrush jpegoptim gifsicle libwebp-dev libheif-dev libjpeg-dev libpng-dev
wget http://www.jonof.id.au/files/kenutils/pngout-20200115-linux.tar.gz
tar -xf pngout-20200115-linux.tar.gz
rm pngout-20200115-linux.tar.gz
cp pngout-20200115-linux/amd64/pngout /bin/pngout && rm -rf pngout-20200115-linux
wget https://imagemagick.org/archive/ImageMagick.tar.gz
tar -xzf ImageMagick.tar.gz
rm -xzf ImageMagick.tar.gz
cd ImageMagick*
./configure --with-modules
make
make install
ldconfig /usr/local/lib
cd ..
rm -rf ImageMagick*
wget https://pecl.php.net/get/imagick-3.7.0.tgz
tar -xzf imagick-3.7.0.tgz
rm imagick-3.7.0.tgz
cd imagick-*
phpize && ./configure
make
make install
echo "extension=imagick.so" >> /etc/php/8.1/fpm/conf.d/20-imagic.ini
cd ..
rm -rf imagick-*
php -r "print_r(Imagick::queryFormats());"