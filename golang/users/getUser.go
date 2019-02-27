package users

import (
	"net/http"
	"log"
	"os"
	"github.com/labstack/echo"
	"database/sql"
	_ "github.com/go-sql-driver/mysql"
	"../db"
)

func GetUser(c echo.Context) error {
	request := new(User)
	if err := c.Bind(request); err != nil {
		log.Printf("users/GetUser: %s", err)
		os.Exit(1)
	}

	//validate(request)
	status, responseData := setResponseForGetUser(request)

	if status == http.StatusOK {
		return c.JSON(status, responseData)
	} else if status == http.StatusForbidden {
		return c.JSON(status, http.StatusText(status))
	} else {
		return c.JSON(status, http.StatusText(status))
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
	dbConn, dbErr := db.ConnectDB()
	if dbErr != nil {
		log.Printf("users/selectData: dbErr = %s", dbErr)
		os.Exit(1)
	}
	defer dbConn.Close()

	data, err := dbConn.Query("SELECT * FROM users WHERE email=? AND password=?", (*request).Email, (*request).Password)
	if data == nil && err == nil {
		increaseFailureCount(dbConn, request)
		return nil, http.StatusForbidden
	}
	if err != nil {
		log.Printf("users/selectData: err = %s", err)
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
			log.Printf("users/GetUser: err = %s", err)
			os.Exit(1)
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

func increaseFailureCount(dbConn *sql.DB, request *User) {
	data, errSelecting := dbConn.Query("SELECT email, failure_count FROM users WHERE email=?", (*request).Email)
	if errSelecting != nil {
		log.Printf("users/increaseFailureCount: errSelecting = %s", errSelecting)
		os.Exit(1)
	}

	var email string
	var failureCount int

	for data.Next() {
		err := data.Scan(&email, &failureCount)
		if err != nil {
			log.Printf("users/increaseFailureCount: err = %s", err)
			os.Exit(1)
		}
	}

	_, errUpdating := dbConn.Query("UPDATE users SET failure_count=?",failureCount+1)
	if errUpdating != nil {
		log.Printf("users/increaseFailureCount: errUpdating = %s", errUpdating)
		os.Exit(1)
	}
}
