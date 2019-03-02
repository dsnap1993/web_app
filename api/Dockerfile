FROM golang:latest

WORKDIR /go/src/api

ADD . /go/src/api

RUN go get github.com/labstack/echo
RUN go get github.com/google/gopacket
RUN go get github.com/go-sql-driver/mysql
RUN go get github.com/dgrijalva/jwt-go

EXPOSE 3000

#execute main.go
#CMD ["go", "run", "main.go"]
