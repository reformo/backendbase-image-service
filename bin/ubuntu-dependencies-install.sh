apt-get build-essential install webp pngquant optipng pngcrush jpegoptim gifsicle
wget http://www.jonof.id.au/files/kenutils/pngout-20200115-linux.tar.gz
tar -xf pngout-20200115-linux.tar.gz
rm pngout-20200115-linux.tar.gz
cp pngout-20200115-linux/amd64/pngout /bin/pngout && rm -rf pngout-20200115-linux