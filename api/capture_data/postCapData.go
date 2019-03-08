package capture_data

import (
	"net/http"
	"log"
	"os"
	"time"
	"github.com/labstack/echo"
	_ "github.com/go-sql-driver/mysql"
	"../db"
)

type requestForPOST struct {
	UserId 		int		`json:"user_id"`
	DataName	string  `json:"data_name"`
	DataSummary string 	`json:"data_summary"`
}

type responseForPOST struct {
	DataId 		int		`json:"data_id"`
	DataName	string  `json:"data_name"`
	DataSummary	string  `json:"data_summary"`
	FileName	string 	`json:"file_name"`
	CreatedAt	string 	`json:"created_at"`
}

func (request *requestForPOST) validate() (bool, string) {
	errMsg := ""
	result := true
	if (*request).UserId == 0 || (*request).DataName == "" || (*request).DataSummary == "" {
		errMsg = "Please check request parameters."
		result = false
	}
	return result, errMsg
}

func PostCapData(c echo.Context) error {
	request := new(requestForPOST)
	if err := c.Bind(request); err != nil {
		log.Printf("capture_data/PostCapData: %s", err)
		os.Exit(1)
	}

	result, errMsg := request.validate()
	if result == false {
		log.Printf("[response] %d %s", http.StatusBadRequest, errMsg)
		return c.JSON(http.StatusBadRequest, errMsg)
	}

	data, status := insertData(request)
	status, responseData := createResponseForPostCapData(data, status)

	log.Printf("[response] %d %s", status, responseData)
	return c.JSON(status, responseData)
}

func createResponseForPostCapData(data *db.CapDataTable, status int) (int, *responseForPOST) {
	if status == http.StatusCreated {
		responseData := &responseForPOST{
			DataId: (*data).DataId,
			DataName: (*data).DataName,
			DataSummary: (*data).DataSummary,
			FileName: (*data).FileName,
			CreatedAt: (*data).CreatedAt,
		}
		return status, responseData
	} else {
		return status, nil
	}
}

func insertData(request *requestForPOST) (*db.CapDataTable, int) {
	now := time.Now()
	formatedTime := now.Format("2006-01-02 15:04:05")

	dbConn, dbErr := db.ConnectDB()
	if dbErr != nil {
		log.Printf("users/insertData: dbErr = %s", dbErr)
		os.Exit(1)
	}
	defer dbConn.Close()

	stmt, err := dbConn.Prepare(`
        INSERT INTO capture_data (user_id, data_name, data_summary, created_at, file_name)
        VALUES (?, ?, ?, ?, ?)
	`)
    if err != nil {
        log.Printf("capture_data/insertData: err = %s", err)
    }
	defer stmt.Close()

	ret, errExecuting := stmt.Exec((*request).UserId, (*request).DataName, (*request).DataSummary, formatedTime, (*request).DataName)
	if errExecuting != nil {
		log.Printf("capture_data/insertData: errExecuting = %s", errExecuting)
		return nil, http.StatusInternalServerError
	}

	capData := db.CapDataTable{}
	dataId, errLastInsertId := ret.LastInsertId()
	if errExecuting != nil {
		log.Printf("users/insertData: errLastInsertId = %s", errLastInsertId)
	}

	capData.DataId = int(dataId)
	capData.DataName = (*request).DataName
	capData.DataSummary = (*request).DataSummary
	capData.CreatedAt = formatedTime
	capData.FileName = (*request).DataName

	return &capData, http.StatusCreated
}