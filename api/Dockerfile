FROM golang:latest

WORKDIR /go/src/api

ADD . /go/src/api

RUN apt-get update \
&& apt-get install -y \
vim

RUN go get github.com/labstack/echo
RUN go get github.com/google/gopacket
RUN go get github.com/go-sql-driver/mysql
RUN go get github.com/dgrijalva/jwt-go
RUN go get github.com/joho/godotenv

EXPOSE 3000

