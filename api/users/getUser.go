package users

import (
	"net/http"
	"log"
	"os"
	"time"
	"github.com/labstack/echo"
	"database/sql"
	_ "github.com/go-sql-driver/mysql"
	"../db"
)

type request struct {
	UserId 		int		`json:"user_id"`
	Email 		string 	`json:"email"`
	Password 	string 	`json:"password"`
}

type response struct {
	UserId 		int		`json:"user_id"`
	Email 		string 	`json:"email"`
	Name		string  `json:"name"`
}

func GetUser(c echo.Context) error {
	request := new(request)
	if err := c.Bind(request); err != nil {
		log.Printf("users/GetUser: %s", err)
		os.Exit(1)
	}

	//validate(request)
	status, responseData := setResponseForGetUser(request)

	if status == http.StatusOK {
		return c.JSON(status, responseData)
	} else {
		return c.JSON(status, http.StatusText(status))
	}
}

func setResponseForGetUser(request *request) (int, *response) {
	data, status := selectData(request)

	if status == http.StatusOK {
		responseData := &response{
			UserId: (*data).UserId,
			Email: (*data).Email,
			Name: (*data).Name,
		}
		return status, responseData
	} else {
		return status, nil
	}
}

/*func validate(request *request, c echo.Context) {
	if (*request).Email != nil {
		
	}
	if (*request).Password != nil {
		
	}
}*/

func selectData(request *request) (*UsersTable, int) {
	dbConn, dbErr := db.ConnectDB()
	if dbErr != nil {
		log.Printf("users/selectData: dbErr = %s", dbErr)
		os.Exit(1)
	}
	defer dbConn.Close()

	data, err := dbConn.Query("SELECT * FROM users WHERE email=? AND password=?", (*request).Email, (*request).Password)
	if err != nil {
		log.Printf("users/selectData: err = %s", err)
		return nil, http.StatusInternalServerError
	}

	user := UsersTable{}
	count := 0
	
	for data.Next() {
		count++
		var userId int
		var name string
		var email string
		var password string
		var createdAt string
		var updatedAt string
		var isLocked bool
		var failureCount int
		var unlockedAt string

		err := data.Scan(&userId, &name, &email, &password, &createdAt, &updatedAt, &isLocked, &failureCount, &unlockedAt)
		if err != nil {
			log.Printf("users/selectUser: err = %s", err)
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
	// check whether empty set
	if count == 0 {
		if isLockedAccount(dbConn, request) {
			return nil, http.StatusForbidden
		}
		result := increaseFailureCount(dbConn, request)
		if result == 5 { // use the value in .env
			lockAccount(dbConn, request)
		}
		return nil, http.StatusUnauthorized
	}

	if user.IsLocked {
		if isPastedLockingAccount(dbConn, user.Email) {
			unlockAccount(dbConn, user.Email)
		} else {
			return nil, http.StatusForbidden
		}
	}

	return &user, http.StatusOK
}

func increaseFailureCount(dbConn *sql.DB, request *request) int {
	var failureCount int

	errSelecting := dbConn.QueryRow("SELECT failure_count FROM users WHERE email=?", (*request).Email).Scan(&failureCount)
	if errSelecting != nil {
		log.Printf("users/increaseFailureCount: errSelecting = %s", errSelecting)
		os.Exit(1)
	}

	_, errUpdating := dbConn.Query("UPDATE users SET failure_count=? WHERE email=?", failureCount+1, (*request).Email)
	if errUpdating != nil {
		log.Printf("users/increaseFailureCount: errUpdating = %s", errUpdating)
		os.Exit(1)
	}

	return failureCount+1
}

func lockAccount(dbConn *sql.DB, request *request) {
	now := time.Now()
	unlockedAt := now.Add(24 * time.Hour)
	formatedTime := unlockedAt.Format("2006-01-02 15:04:05")

	_, err := dbConn.Query("UPDATE users SET is_locked=1, failure_count=0,unlocked_at=? WHERE email=?", formatedTime, (*request).Email)
	if err != nil {
		log.Printf("users/lockAccount: err = %s", err)
		os.Exit(1)
	}
}

func isLockedAccount(dbConn *sql.DB, request *request) bool {
	var isLocked bool
	err := dbConn.QueryRow("SELECT is_locked FROM users WHERE email=?", (*request).Email).Scan(&isLocked)
	if err != nil {
		log.Printf("users/isLockedAccount: err = %s", err)
		os.Exit(1)
	}
	return isLocked
}

func unlockAccount(dbConn *sql.DB, email string) {
	_, err := dbConn.Query("UPDATE users SET is_locked=0, unlocked_at=0 WHERE email=?", email)
	if err != nil {
		log.Printf("users/unlockAccount: err = %s", err)
		os.Exit(1)
	}
}

func isPastedLockingAccount(dbConn *sql.DB, email string) bool {
	var unlockedAt string
	now := time.Now()

	err := dbConn.QueryRow("SELECT unlocked_at FROM users WHERE email=?", email).Scan(&unlockedAt)
	if err != nil {
		log.Printf("users/isPastedLockingAccount: err = %s", err)
		os.Exit(1)
	}
	if strToTime(unlockedAt).Before(now) {
		return true
	} else {
		return false
	}
}
