package users

import (
	"net/http"
	"github.com/labstack/echo"
	"github.com/go-sql-driver/mysql"
)

func GetUser() echo.HandlerFunc {
	c echo.Context
	validate(c)
	data, err := selectData(c)
	if err == nil {
		return c.String(http.StatusOK, data)
	} else if err == "403" {
		return c.String(http.StatusForbidden)
	} else {
		return c.String(http.StatusInternalServerError)
	}
}

func validate(c echo.Context) {
	// validate request parameters
}

func selectData(c echo.Context) (string, error) {
	// select data from users table
}
