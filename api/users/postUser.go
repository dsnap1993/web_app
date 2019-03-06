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

type requestForPOST struct {
	Name		string  `json:"name"`
	Email 		string 	`json:"email"`
	Password 	string 	`json:"password"`
}

type responseForPOST struct {
	UserId 		int		`json:"user_id"`
	Name		string  `json:"name"`
	Email 		string 	`json:"email"`
}

func (req *requestForPOST) hashPassword() string {
	//env.LoadEnv()
	cost := 10
	hashPass, err := bcrypt.GenerateFromPassword([]byte((*req).Password), cost)
	if err != nil {
		log.Printf("requestForPOST/hashPassword: err = %s", err)
	}
	return string(hashPass)
}

func PostUser(c echo.Context) error {
	request := new(requestForPOST)
	if err := c.Bind(request); err != nil {
		log.Printf("users/PostUser: %s", err)
		os.Exit(1)
	}

	//validate(request)
	data, status := insertData(request)
	status, responseData := createResponseForPostUser(data, status)

	if status == http.StatusCreated {
		log.Printf("[response] %d %s", status, responseData)
		return c.JSON(status, responseData)
	} else {
		return c.JSON(status, nil)
	}
}

func createResponseForPostUser(data *db.UsersTable, status int) (int, *responseForPOST) {
	if status == http.StatusCreated {
		responseData := &responseForPOST{
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

func insertData(request *requestForPOST) (*db.UsersTable, int) {
	now := time.Now()
	formatedTime := now.Format("2006-01-02 15:04:05")

	dbConn, dbErr := db.ConnectDB()
	if dbErr != nil {
		log.Printf("users/insertData: dbErr = %s", dbErr)
		os.Exit(1)
	}
	defer dbConn.Close()

	stmt, err := dbConn.Prepare(`
        INSERT INTO users (name, email, password, created_at)
        VALUES (?, ?, ?, ?)
	`)
    if err != nil {
        log.Printf("users/insertData: err = %s", err)
    }
	defer stmt.Close()

	hashPass := request.hashPassword()
	ret, errExecuting := stmt.Exec((*request).Name, (*request).Email, hashPass, formatedTime)
	if errExecuting != nil {
		log.Printf("users/insertData: errExecuting = %s", errExecuting)
		return nil, http.StatusInternalServerError
	}

	user := db.UsersTable{}
	userId, errLastInsertId := ret.LastInsertId()
	if errExecuting != nil {
		log.Printf("users/insertData: errLastInsertId = %s", errLastInsertId)
	}

	user.UserId = int(userId)
	user.Name = (*request).Name
	user.Email = (*request).Email

	return &user, http.StatusCreated
}
