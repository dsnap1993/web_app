package users

import (
	"net/http"
	"log"
	"os"
	"time"
	"github.com/labstack/echo"
	_ "github.com/go-sql-driver/mysql"
	"golang.org/x/crypto/bcrypt"
	"../db"
	//"../env"
)

type requestForPUT struct {
	UserId 		int		`json:"user_id"`
	Name		string  `json:"name"`
	Email 		string 	`json:"email"`
	Password 	string 	`json:"password"`
}

type responseForPUT struct {
	UserId 		int		`json:"user_id"`
	Name		string  `json:"name"`
	Email 		string 	`json:"email"`
}

func (request *requestForPUT) validate() (bool, string) {
	errMsg := ""
	result := true
	if (*request).UserId == nil || (*request).Email == "" || (*request).Email == "" || (*request).Password == "" {
		errMsg = "Please check request parameters."
		result = false
	}
	return result, errMsg
}

func (req *requestForPUT) hashPassword() string {
	//env.LoadEnv()
	cost := 10
	hashPass, err := bcrypt.GenerateFromPassword([]byte((*req).Password), cost)
	if err != nil {
		log.Printf("requestForPOST/hashPassword: err = %s", err)
	}
	return string(hashPass)
}

func PutUser(c echo.Context) error {
	request := new(requestForPUT)
	if err := c.Bind(request); err != nil {
		log.Printf("users/PutUser: %s", err)
		os.Exit(1)
	}

	result, errMsg := request.validate()
	if result == false {
		log.Printf("[response] %d %s", http.StatusBadRequest, errMsg)
		return c.JSON(http.StatusBadRequest, errMsg)
	}

	data, status := updateData(request)
	status, responseData := createResponseForPutUser(data, status)

	if status == http.StatusOK {
		log.Printf("[response] %d %s", status, responseData)
		return c.JSON(status, responseData)
	} else {
		log.Printf("[response] %d", status)
		return c.JSON(status, nil)
	}
}

func createResponseForPutUser(data *db.UsersTable, status int) (int, *responseForPUT) {
	if status == http.StatusOK {
		responseData := &responseForPUT{
			UserId: (*data).UserId,
			Email: (*data).Email,
			Name: (*data).Name,
		}
		return status, responseData
	} else {
		return status, nil
	}
}

func updateData(request *requestForPUT) (*db.UsersTable, int) {
	now := time.Now()
	formatedTime := now.Format("2006-01-02 15:04:05")

	dbConn, dbErr := db.ConnectDB()
	if dbErr != nil {
		log.Printf("users/updateData: dbErr = %s", dbErr)
		os.Exit(1)
	}
	defer dbConn.Close()

	stmt, err := dbConn.Prepare(`
        UPDATE users SET name=?, email=?, password=?, updated_at=? WHERE user_id=?
	`)
    if err != nil {
        log.Printf("users/updateData: err = %s", err)
    }
	defer stmt.Close()

	hashPass := request.hashPassword()
	_, errExecuting := stmt.Exec((*request).Name, (*request).Email, hashPass, formatedTime, (*request).UserId)
	if errExecuting != nil {
		log.Printf("users/updateData: errExecuting = %s", errExecuting)
		return nil, http.StatusInternalServerError
	}

	user := db.UsersTable{}
	user.UserId = (*request).UserId
	user.Name = (*request).Name
	user.Email = (*request).Email

	return &user, http.StatusOK
}
