package users

import (
	"net/http"
	"log"
	"os"
	"github.com/labstack/echo"
	_ "github.com/go-sql-driver/mysql"
	"../db"
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

func PutUser(c echo.Context) error {
	request := new(requestForPUT)
	if err := c.Bind(request); err != nil {
		log.Printf("users/PutUser: %s", err)
		os.Exit(1)
	}

	//validate(request)
	data, status := updateData(request)
	status, responseData := createResponseForPutUser(data, status)

	if status == http.StatusOK {
		log.Printf("[response] %d %s", status, responseData)
		return c.JSON(status, responseData)
	} else {
		return c.JSON(status, http.StatusText(status))
	}
}

func createResponseForPutUser(data *UsersTable, status int) (int, *responseForPUT) {
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

/*func validate(request *request, c echo.Context) {
	if (*request).Email != nil {
		
	}
	if (*request).Password != nil {
		
	}
}*/

func updateData(request *requestForPUT) (*UsersTable, int) {
	dbConn, dbErr := db.ConnectDB()
	if dbErr != nil {
		log.Printf("users/updateData: dbErr = %s", dbErr)
		os.Exit(1)
	}
	defer dbConn.Close()

	stmt, err := dbConn.Prepare(`
        UPDATE users SET name=?, email=?, password=? WHERE user_id=?
	`)
    if err != nil {
        log.Printf("users/updateData: err = %s", err)
    }
	defer stmt.Close()

	_, errExecuting := stmt.Exec((*request).Name, (*request).Email, (*request).Password, (*request).UserId)
	if errExecuting != nil {
		log.Printf("users/updateData: errExecuting = %s", errExecuting)
		return nil, http.StatusInternalServerError
	}

	user := UsersTable{}
	user.UserId = (*request).UserId
	user.Name = (*request).Name
	user.Email = (*request).Email

	return &user, http.StatusOK
}