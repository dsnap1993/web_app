package users

import (
	"net/http"
	"strings"
	"database/mysql"
	"github.com/labstack/echo"
	_ "github.com/go-sql-driver/mysql"
	"./database"
)

func GetUser() echo.HandlerFunc {
	request := new(User)
	if err = c.Bind(request); err != nil {
		return
	}

	validate(request)

	func(c echo.Context) error {
		responseData, err := selectData(request)

		if err != nil {
			return c.Json(http.StatusOK, responseData)
		} else if err == "403" {
			return c.String(http.StatusForbidden)
		} else {
			return c.String(http.StatusInternalServerError)
		}
	}
}

func validate(request RequestParam) {
	// validate request parameters
}

func selectData(request RequestParam) (*User, error) {
	db := database.ConnectDB()
	defer db.Close()

	data, err := db.Query("SELECT * FROM users WHERE email=? AND password=?",request.Email, request.password)
	if err != nil {
		// if data == nil, increase failureCount and return "403". otherwise return "error"
	}

	user := UsersTable{}
	
	for data.Next() {
		var userId int
		var name string
		var email string
		var password string
		var createdAt string
		var updatedAt string
		var isLocked int
		var failureCount int
		var unlockedAt string

		err := data.Scan(&userId, &name, &email, &password, &createdAt, &updatedAt, &isLocked, &failureCount, &unlockedAt)
		if err != nil {
			panic(err.Error())
		}
		user.UserId = userId
		user.Name = name
		user.Email = email
		user.Password = password
		user.CreatedAt = createdAt
		user.UpdatedAr = updatedAt
		user.IsLocked = isLocked
		user.FailureCount = failureCount
		user.UnlockedAt = unlockedAt
	}

	// check whether locking account

	return user
}
