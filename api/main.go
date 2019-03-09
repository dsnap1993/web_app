package main

import (
	"os"
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

	// middleware
	//e.Use(middleware.Logger())
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

	//e.Logger.Fatal(e.Start(":3000"))
	e.Start(":3000")
}
