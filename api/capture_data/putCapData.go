package capture_data

import (
	"net/http"
	"log"
	"os"
	"github.com/labstack/echo"
	_ "github.com/go-sql-driver/mysql"
	"../db"
)

type requestForPUT struct {
	DataId 		int		`json:"data_id"`
	DataName	string  `json:"data_name"`
	DataSummary	string 	`json:"data_summary"`
}

type responseForPUT struct {
	DataId 		int		`json:"data_id"`
	DataName	string  `json:"data_name"`
	DataSummary	string 	`json:"data_summary"`
}

func (request *requestForPUT) validate() (bool, string) {
	errMsg := ""
	result := true
	if (*request).DataId == 0 || (*request).DataName == "" || (*request).DataSummary == "" {
		errMsg = "Please check request parameters."
		result = false
	}
	return result, errMsg
}

func PutCapData(c echo.Context) error {
	request := new(requestForPUT)
	if err := c.Bind(request); err != nil {
		log.Printf("capture_data/PutCapData: %s", err)
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
	dbConn, dbErr := db.ConnectDB()
	if dbErr != nil {
		log.Printf("capture_data/updateData: dbErr = %s", dbErr)
		os.Exit(1)
	}
	defer dbConn.Close()

	stmt, err := dbConn.Prepare(`
        UPDATE capture_data SET data_name=?, data_summary=? WHERE data_id=?
	`)
    if err != nil {
        log.Printf("capture_data/updateData: err = %s", err)
    }
	defer stmt.Close()

	_, errExecuting := stmt.Exec((*request).DataName, (*request).DataSummary, (*request).DataId)
	if errExecuting != nil {
		log.Printf("capture_data/updateData: errExecuting = %s", errExecuting)
		return nil, http.StatusInternalServerError
	}

	responseData := responseForPUT{}
	responseData.DataId = (*request).DataId
	responseData.DataName = (*request).DataName
	responseData.DataSummary = (*request).DataSummary

	return &responseData, http.StatusOK
}
