FROM golang:latest

ADD . /go/src/api

RUN go get github.com/labstack/echo
RUN go get github.com/google/gopacket

EXPOSE 3000
