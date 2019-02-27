package main

import (
	"github.com/labstack/echo"
	"github.com/labstack/echo/middleware"
	"./users"
)

func main() {
	e := echo.New()

	// middleware
	e.Use(middleware.Logger())
	e.Use(middleware.Recover())

	// routes
	e.GET("/users", users.GetUser)

	e.Logger.Fatal(e.Start(":3000"))
}
