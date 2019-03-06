package main

import (
	"github.com/labstack/echo"
	"github.com/labstack/echo/middleware"
	"./users"
	"./login"
)

func main() {
	e := echo.New()

	// middleware
	//e.Use(middleware.Logger())
	e.Use(middleware.Recover())

	/* routes */
	// /login
	e.POST("/login", login.PostLogin)
	// /users
	e.POST("/users", users.PostUser)
	e.PUT("/users", users.PutUser)

	//e.Logger.Fatal(e.Start(":3000"))
	e.Start(":3000")
}
