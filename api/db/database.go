package db

import (
	"fmt"
	"log"
	"database/sql"
	_ "github.com/go-sql-driver/mysql"
)

func ConnectDB() (*sql.DB, error) {
	host := "192.168.0.4"
	port := "3306"
	user := "web_app"
	passwd := "password"
	dbname := "web_app"
	protocol := "tcp"

	connectionInfo := fmt.Sprintf("%s:%s@%s(%s:%s)/%s",
		user, passwd, protocol, host, port, dbname)
	log.Printf("db/ConnectDB: connectionInfo = %s", connectionInfo)

	db, err := sql.Open("mysql", connectionInfo)
	if err != nil {
		log.Printf("db/ConnectDB: %s", err)
		return nil, err
	}
	return db, nil
}

// get connection info from .env