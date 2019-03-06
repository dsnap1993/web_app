package main

import (
	"os"
	"github.com/labstack/echo"
	"github.com/labstack/echo/middleware"
	"./users"
	"./login"
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
	// /login
	e.POST(pathLogin, login.PostLogin)
	// /users
	e.POST(pathUser, users.PostUser)
	e.PUT(pathUser, users.PutUser)

	//e.Logger.Fatal(e.Start(":3000"))
	e.Start(":3000")
}
