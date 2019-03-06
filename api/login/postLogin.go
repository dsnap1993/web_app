package login

import (
	"net/http"
	"log"
	"os"
	"time"
	"github.com/labstack/echo"
	"database/sql"
	_ "github.com/go-sql-driver/mysql"
	"golang.org/x/crypto/bcrypt"
	"../db"
	//"../env"
)

type request struct {
	Email 		string 	`json:"email"`
	Password 	string 	`json:"password"`
}

type response struct {
	UserId 		int		`json:"user_id"`
	Email 		string 	`json:"email"`
	Name		string  `json:"name"`
}

func (request *request) validate() (bool, string) {
	errMsg := ""
	result := true
	if (*request).Email == "" || (*request).Password == ""{
		errMsg = "Please check request parameters."
		result = false
	}
	return result, errMsg
}

func PostLogin(c echo.Context) error {
	request := new(request)
	if err := c.Bind(request); err != nil {
		log.Printf("login/GetUser: %s", err)
		os.Exit(1)
	}

	result, errMsg := request.validate()
	if result == false {
		log.Printf("[response] %d %s", http.StatusBadRequest, errMsg)
		return c.JSON(http.StatusBadRequest, errMsg)
	}

	data, status := selectData(request)
	status, responseData := createResponse(data, status)

	if status == http.StatusOK {
		log.Printf("[response] %d %s", status, responseData)
		return c.JSON(status, responseData)
	} else {
		log.Printf("[response] %d", status)
		return c.JSON(status, nil)
	}
}

func createResponse(data *db.UsersTable, status int) (int, *response) {
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

func selectData(request *request) (*db.UsersTable, int) {
	//env.LoadEnv()
	dbConn, dbErr := db.ConnectDB()
	if dbErr != nil {
		log.Printf("login/selectData: dbErr = %s", dbErr)
		os.Exit(1)
	}
	defer dbConn.Close()

	data, err := dbConn.Query("SELECT * FROM users WHERE email=?", (*request).Email)
	if err != nil {
		log.Printf("login/selectData: err = %s", err)
		return nil, http.StatusInternalServerError
	}

	user := db.UsersTable{}
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
			log.Printf("login/selectUser: err = %s", err)
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
		return nil, http.StatusBadRequest
	}

	errComparingPasswd := bcrypt.CompareHashAndPassword([]byte(user.Password), []byte((*request).Password))

	if errComparingPasswd != nil {
		log.Printf("login/selectUser: errComparingPasswd = %s", errComparingPasswd)
		if isLockedAccount(dbConn, request) {
			return nil, http.StatusForbidden
		}
		result := increaseFailureCount(dbConn, request)
		if result == 5 {
			lockAccount(dbConn, request)
		}
		return nil, http.StatusUnauthorized
	}

	if user.IsLocked {
		if isPastedLockingPeriod(dbConn, user.Email) {
			unlockAccount(dbConn, user.Email)
		} else {
			return nil, http.StatusForbidden
		}
	}

	// reset failure_count
	if user.FailureCount != 0 {
		updateUsers(dbConn, user.Email, 0)
	}

	return &user, http.StatusOK
}

func increaseFailureCount(dbConn *sql.DB, request *request) int {
	var failureCount int

	errSelecting := dbConn.QueryRow("SELECT failure_count FROM users WHERE email=?", (*request).Email).Scan(&failureCount)
	if errSelecting != nil {
		log.Printf("login/increaseFailureCount: errSelecting = %s", errSelecting)
		os.Exit(1)
	}

	updateUsers(dbConn, (*request).Email, failureCount+1)

	return failureCount+1
}

func updateUsers(dbConn *sql.DB, email string, failureCount int) {
	_, err := dbConn.Query("UPDATE users SET failure_count=? WHERE email=?", failureCount, email)
	if err != nil {
		log.Printf("login/updateUsers: err = %s", err)
		os.Exit(1)
	}
}

func lockAccount(dbConn *sql.DB, request *request) {
	now := time.Now()
	unlockedAt := now.Add(24 * time.Hour)
	formatedTime := unlockedAt.Format("2006-01-02 15:04:05")

	_, err := dbConn.Query("UPDATE users SET is_locked=1, failure_count=0,unlocked_at=? WHERE email=?", formatedTime, (*request).Email)
	if err != nil {
		log.Printf("login/lockAccount: err = %s", err)
		os.Exit(1)
	}
}

func isLockedAccount(dbConn *sql.DB, request *request) bool {
	var isLocked bool
	err := dbConn.QueryRow("SELECT is_locked FROM users WHERE email=?", (*request).Email).Scan(&isLocked)
	if err != nil {
		log.Printf("login/isLockedAccount: err = %s", err)
		os.Exit(1)
	}
	return isLocked
}

func unlockAccount(dbConn *sql.DB, email string) {
	_, err := dbConn.Query("UPDATE users SET is_locked=0, unlocked_at=0 WHERE email=?", email)
	if err != nil {
		log.Printf("login/unlockAccount: err = %s", err)
		os.Exit(1)
	}
}

func isPastedLockingPeriod(dbConn *sql.DB, email string) bool {
	var unlockedAt string
	now := time.Now()

	err := dbConn.QueryRow("SELECT unlocked_at FROM users WHERE email=?", email).Scan(&unlockedAt)
	if err != nil {
		log.Printf("login/isPastedLockingAccount: err = %s", err)
		os.Exit(1)
	}
	if strToTime(unlockedAt).Before(now) {
		return true
	} else {
		return false
	}
}

func strToTime(str string) time.Time {
	t, err := time.Parse("2006-01-02 15:04:05", str)
	if err != nil {
		log.Printf("login/strToTime: err = %s", err)
		os.Exit(1)
	}
	return t
}
