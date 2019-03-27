package main

import (
	"os"
	//"time"
	"github.com/labstack/echo"
	"github.com/labstack/echo/middleware"
	"./users"
	"./login"
	"./capture_data"
	"./env"
)

func main() {
	env.LoadEnv()
	e := echo.New()

	// log file
	/*now := time.Now()
	formatedTime := now.Format("2006-01-02")
	fileName := "/" + formatedTime + ".log"
	fp, err := os.OpenFile("../../logs" + fileName, os.O_RDWR|os.O_CREATE|os.O_APPEND, 0666)
	if err != nil {
		panic(err)
	}
	// middleware
	e.Use(middleware.LoggerWithConfig(middleware.LoggerConfig{
		Format: "${host} [${time_rfc3339_nano}] \"${method} ${uri}\" ${status} ${bytes_in} ${bytes_out}\n",
		Output: fp,
	}))*/
	e.Use(middleware.Recover())

	/* routes */
	pathLogin := os.Getenv("PATH_COMMON") + os.Getenv("PATH_LOGIN")
	pathUser := os.Getenv("PATH_COMMON") + os.Getenv("PATH_USERS")
	pathCapData := os.Getenv("PATH_COMMON") + os.Getenv("PATH_CAPDATA")
	// /login.json
	e.POST(pathLogin, login.PostLogin)
	// /users.json
	e.POST(pathUser, users.PostUser)
	e.PUT(pathUser, users.PutUser)
	// /capture_data.json
	e.GET(pathCapData, capture_data.GetCapData)
	e.POST(pathCapData, capture_data.PostCapData)
	e.PUT(pathCapData, capture_data.PutCapData)
	e.DELETE(pathCapData, capture_data.DeleteCapData)

	e.Logger.Fatal(e.Start(":3000"))
}
