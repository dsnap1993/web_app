package response

import (
	"net/http"
	"github.com/labstack/echo"
)

func SetErrorResponse (c echo.Context, status int) {
	log.Printf("[response] %d %s", status, http.StatusText(status))
	return c.JSON(status, http.StatusText(status))
}