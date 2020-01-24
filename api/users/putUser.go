package users

import (
	"log"
	"net/http"
	"os"
	"time"

	"../db"
	_ "github.com/go-sql-driver/mysql"
	"github.com/labstack/echo"
	"golang.org/x/crypto/bcrypt"
	//"../env"
)

type requestForPUT struct {
	UserId   int    `json:"user_id"`
	Name     string `json:"name"`
	Email    string `json:"email"`
	Password string `json:"password"`
}

type responseForPUT struct {
	UserId int    `json:"user_id"`
	Name   string `json:"name"`
	Email  string `json:"email"`
}

func (request *requestForPUT) validate() (bool, string) {
	errMsg := ""
	result := true
	if (*request).UserId == 0 || (*request).Email == "" || (*request).Email == "" || (*request).Password == "" {
		errMsg = "Please check request parameters."
		result = false
	}
	return result, errMsg
}

func (req *requestForPUT) hashPassword() string {
	//env.LoadEnv()
	cost := 10
	hashedPasswd, err := bcrypt.GenerateFromPassword([]byte((*req).Password), cost)
	if err != nil {
		log.Printf("requestForPUT/hashPassword: err = %s", err)
	}
	return string(hashedPasswd)
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

	responseData, status := updateData(request)

	log.Printf("[response] %d %s", status, &responseData)
	return c.JSON(status, &responseData)
}

func updateData(request *requestForPUT) (*responseForPUT, int) {
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

	hashedPasswd := request.hashPassword()
	_, errExecuting := stmt.Exec((*request).Name, (*request).Email, hashedPasswd, formatedTime, (*request).UserId)
	if errExecuting != nil {
		log.Printf("users/updateData: errExecuting = %s", errExecuting)
		return nil, http.StatusInternalServerError
	}

	responseData := responseForPUT{}
	responseData.UserId = (*request).UserId
	responseData.Name = (*request).Name
	responseData.Email = (*request).Email

	return &responseData, http.StatusOK
}
