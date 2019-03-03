package main

import (
	"github.com/labstack/echo"
	"github.com/labstack/echo/middleware"
	"./users"
)

func main() {
	e := echo.New()

	// middleware
	//e.Use(middleware.Logger())
	e.Use(middleware.Recover())

	/* routes */
	// /users
	e.GET("/users", users.GetUser)
	e.POST("/users", users.PostUser)
	e.PUT("/users", users.PutUser)

	//e.Logger.Fatal(e.Start(":3000"))
	e.Start(":3000")
}
