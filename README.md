
# Random DataMatrix Generator

## Usage

### PHP

* php generator.php

[![Editor Screen](https://raw.githubusercontent.com/maranemil/random_datamatrix_generator/master/screens/screen_php_gen.png)](#features)

### Python3

* python3  generator.py

[![Editor Screen](https://raw.githubusercontent.com/maranemil/random_datamatrix_generator/master/screens/screen%20py3_gen.png)](#features)

### ------------------------------------------------------------

## Requirements

### dmtx ( LINUX DataMatrix R/W)

#### Installation

* sudo apt-get update
* sudo apt-get install dmtx-utils

#### Usage 
+ od -An -vtu1 # for decimal
+ od -An -vtx1 # for hexadecimal


### TCPDF ( PHP DataMatrix generator )

#### Installation

* git clone https://github.com/tecnickcom/tcpdf

### pystrich ( Python DataMatrix generator )

+ https://github.com/mmulqueen/pyStrich
+ pip3 install pystrich


## DataMatrix Software Checker (Windows)

#### bcTester
* https://www.bctester.de/de/home.html

#### Matrixcode-Checker
* https://www.deutschepost.de/de/p/premiumadress/downloads.html


#### Matrixcode-Checker 26x26 cmd
* gs -sDEVICE=jpeg -dPDFSETTINGS=/screen -r300x300 -dJPEGQ=55 -dQFactor=0.5 -dBATCH -dNOPAUSE -dSAFER -dQUIET -sOutputFile=/path/file.jpg /path/file.pdf
* gs -sDEVICE=jpeg -dPDFSETTINGS=/prepress -r300x300 -dJPEGQ=55 -dQFactor=0.5 -dBATCH -dNOPAUSE -dSAFER -dQUIET -sOutputFile=/path/file.jpg /path/file.pdf
* gs -sDEVICE=jpeg -dPDFSETTINGS=/ebook -r300 -dJPEGQ=55 -dQFactor=0.5 -dBATCH -dNOPAUSE -dSAFER -dQUIET -sOutputFile=/path/file.jpg /path/file.pdf
* dmtxread -v -N 1 /path/file.jpg 2>&1 | head -n +2 | cut -d '-' -f1 | tr -d '[:space:]' | grep -i '26x26'

