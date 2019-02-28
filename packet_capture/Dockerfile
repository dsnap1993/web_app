FROM golang:latest

RUN apt-get update \
&& apt-get install -y \
libpcap-dev
#libnet

WORKDIR /go/src/packet_capture

ADD . /go/src/packet_capture

RUN go get github.com/google/gopacket

EXPOSE 4000
