package capture_data

import (
	"net/http"
	"log"
	"os"
	"github.com/labstack/echo"
	"../db"
)

type requestForDELETE struct {
	DataId 		int		`json:"data_id"`
}

type responseForDELETE struct {
	DataId 		int		`json:"data_id"`
}

func (request *requestForDELETE) validate() (bool, string) {
	errMsg := ""
	result := true
	if (*request).DataId == 0 {
		errMsg = "Please check a request parameter."
		result = false
	}
	return result, errMsg
}

func DeleteCapData(c echo.Context) error {
	request := new(requestForDELETE)
	if err := c.Bind(request); err != nil {
		log.Printf("capture_data/DeleteCapData: %s", err)
		os.Exit(1)
	}

	result, errMsg := request.validate()
	if result == false {
		log.Printf("[response] %d %s", http.StatusBadRequest, errMsg)
		return c.JSON(http.StatusBadRequest, errMsg)
	}

	responseData, status := deleteData(request)

	log.Printf("[response] %d %s", status, &responseData)
	return c.JSON(status, &responseData)
}

func deleteData(request *requestForDELETE) (*responseForDELETE, int) {
	dbConn, dbErr := db.ConnectDB()
	if dbErr != nil {
		log.Printf("capture_data/deleteData: dbErr = %s", dbErr)
		os.Exit(1)
	}
	defer dbConn.Close()

	stmt, err := dbConn.Prepare(`
        DELETE FROM capture_data WHERE data_id=?
	`)
    if err != nil {
        log.Printf("capture_data/deleteData: err = %s", err)
    }
	defer stmt.Close()

	_, errExecuting := stmt.Exec((*request).DataId)
	if errExecuting != nil {
		log.Printf("capture_data/deleteData: errExecuting = %s", errExecuting)
		return nil, http.StatusInternalServerError
	}

	data := responseForDELETE{}
	data.DataId = (*request).DataId

	return &data, http.StatusOK
}