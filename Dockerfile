FROM archlinux/base

RUN pacman -Syyu --noconfirm
RUN pacman -S --noconfirm python ansible make unzip php

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"  && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer  && \
    php -r "unlink('composer-setup.php');"

COPY Makefile /whhato/Makefile
COPY ansible /whhato/ansible
COPY project /whhato/project

WORKDIR /whhato

CMD /bin/bash
#CMD /usr/sbin/make