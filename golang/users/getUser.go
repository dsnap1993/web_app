package users

import (
	"net/http"
	"strings"
	"database/mysql"
	"github.com/labstack/echo"
	_ "github.com/go-sql-driver/mysql"
	"../database"
)

func GetUser() echo.HandlerFunc {
	var c echo.Context
	*request := new(User)
	if err = c.Bind(request); err != nil {
		return
	}

	//validate(request)
	setResponseForGetUser(request, c)
}

func setResponseForGetUser(request *User, c echo.Context) error {
	data, err := selectData(request)
	*responseData := new(User)
	responseData.UserId = data.UserId
	responseData.Email = data.Email
	responseData.Name = data.Name

	if err == nil {
		return c.Json(http.StatusOK, responseData)
	} else if err == "403" {
		return c.Json(http.StatusForbidden)
	} else {
		return c.Json(http.StatusInternalServerError)
	}
}

func validate(request *User, c echo.Context) {
	if request.Email != nil {
		//
	}
	if request.Password != nil {
		//
	}
}

func selectData(request *User) (*UsersTable, string) {
	db := database.ConnectDB()
	defer db.Close()

	data, err := db.Query("SELECT * FROM users WHERE email=? AND password=?",request.Email, request.password)
	if data == nil {
		increaseFailureCount(db, request)
		return nil, "403"
	}
	if err != nil {
		return nil, "error"
	}

	*user := UsersTable{}
	
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

	return user, nil
}

func increaseFailureCount(db *sql.DB, request *User) {
	data, err := db.Query("SELECT email, failure_count FROM users WHERE email=?",request.Email)
	if err != nil {
		panic(err.Error())
	}

	var email string
	var failureCount int

	for data.Next() {
		err := data.Scan(&email, &failureCount)
		if err != nil {
			panic(err.Error())
		}
	}

	_, err := db.Query("UPDATE users SET failure_count=?",failureCount+1)
	if err != nil {
		panic(err.Error())
	}
}