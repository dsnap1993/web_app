package db

import (
	"database/sql"
	_ "github.com/go-sql-driver/mysql"
)

func ConnectDB() (*sql.DB) {
	dbDriver := "mysql"
	dbUser := "web_app"
	dbPasswd := "password"
	dbName := "web_app"

	db, err := sql.Open(dbDriver, dbUser+":"+dbPasswd+"@/"+dbName)
	if err != nil {
		panic(err.Error())
	}
	return db
}