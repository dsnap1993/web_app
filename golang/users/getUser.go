package users

import (
	"net/http"
	"github.com/labstack/echo"
	"database/sql"
	_ "github.com/go-sql-driver/mysql"
)

func GetUser() echo.HandlerFunc {
	var c echo.Context
	request := new(User)
	if err := c.Bind(request); err != nil {
		panic(err.Error())
	}

	//validate(request)
	status,responseData := setResponseForGetUser(request)
	if status == http.StatusOK {
		return c.JSON(status, responseData)
	} else if status == http.StatusForbidden {
		return c.JSON(status)
	} else {
		return c.JSON(status)
	}
}

func setResponseForGetUser(request *User) (int, *User) {
	data, status := selectData(request)

	if status == http.StatusOK {
		responseData := &User{
			UserId: (*data).UserId,
			Email: (*data).Email,
			Name: (*data).Name,
		}
		return status, responseData
	} else {
		return status, nil
	}
}

/*func validate(request *User, c echo.Context) {
	if (*request).Email != nil {
		
	}
	if (*request).Password != nil {
		
	}
}*/

func selectData(request *User) (*UsersTable, int) {
	db := db.ConnectDB()
	defer db.Close()

	data, err := db.Query("SELECT * FROM users WHERE email=? AND password=?",(*request).Email, (*request).Password)
	if data == nil {
		increaseFailureCount(db, request)
		return nil, http.StatusForbidden
	}
	if err != nil {
		return nil, http.StatusInternalServerError
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
		user.UpdatedAt = updatedAt
		user.IsLocked = isLocked
		user.FailureCount = failureCount
		user.UnlockedAt = unlockedAt
	}

	// check whether locking account

	return &user, http.StatusOK
}

func increaseFailureCount(db *sql.DB, request *User) {
	data, errSelecting := db.Query("SELECT email, failure_count FROM users WHERE email=?",(*request).Email)
	if errSelecting != nil {
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

	_, errUpdateing := db.Query("UPDATE users SET failure_count=?",failureCount+1)
	if errSelecting != nil {
		panic(err.Error())
	}
}