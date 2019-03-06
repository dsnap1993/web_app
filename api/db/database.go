package db

import (
	"fmt"
	"log"
	"os"
	"database/sql"
	_ "github.com/go-sql-driver/mysql"
	"../env"
)

func ConnectDB() (*sql.DB, error) {
	env.LoadEnv()

	host := os.Getenv("DB_HOST")
	port := os.Getenv("DB_PORT")
	user := os.Getenv("DB_USER")
	passwd := os.Getenv("DB_PASSWD")
	dbname := os.Getenv("DB_NAME")
	protocol := os.Getenv("DB_PROTOCOL")

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
