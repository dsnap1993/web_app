package capture_data

import (
	"net/http"
	"log"
	"os"
	"github.com/labstack/echo"
	"../db"
)

type responseForGET struct {
	DataId		int		`json:"data_id"`
	UserId		int		`json:"user_id"`
	DataName	string	`json:"data_name"`
	DataSummary	string	`json:"data_summary"`
	CreatedAt	string	`json:"created_at"`
	FileName	string	`json:"file_name"`
}

func GetCapData(c echo.Context) error {
	userId := c.QueryParam("user_id")
	data, status := selectCapData(userId)
	status, responseData := createResponse(data, status)

	if status == http.StatusOK {
		log.Printf("[response] %d %s", status, responseData)
		return c.JSON(status, responseData)
	} else {
		log.Printf("[response] %d", status)
		return c.JSON(status, nil)
	}
}

func createResponse(data *db.CapDataTable, status int) (int, *responseForGET) {
	if status == http.StatusOK {
		responseData := &responseForGET{
			DataId: (*data).DataId,
			UserId: (*data).UserId,
			DataName: (*data).DataName,
			DataSummary: (*data).DataSummary,
			CreatedAt: (*data).CreatedAt,
			FileName: (*data).FileName,
		}

		return status, responseData
	} else {
		return status, nil
	}
}

func selectCapData(userId string) (*db.CapDataTable, int) {
	dbConn, dbErr := db.ConnectDB()
	if dbErr != nil {
		log.Printf("capture_data/selectCapData: dbErr = %s", dbErr)
		os.Exit(1)
	}
	defer dbConn.Close()

	data, err := dbConn.Query("SELECT * FROM capture_data WHERE user_id=?", userId)
	if err != nil {
		log.Printf("capture_data/selectCapData: err = %s", err)
		return nil, http.StatusInternalServerError
	}

	capData := db.CapDataTable{}
	count := 0
	
	for data.Next() {
		count++
		var dataId int
		var userId int
		var dataName string
		var dataSummary string
		var createdAt string
		var fileName string

		err := data.Scan(&dataId, &userId, &dataName, &dataSummary, &createdAt, &fileName)
		if err != nil {
			log.Printf("capture_data/selectCapData: err = %s", err)
			os.Exit(1)
		}
		capData.DataId = dataId
		capData.UserId = userId
		capData.DataName = dataName
		capData.DataSummary = dataSummary
		capData.CreatedAt = createdAt
		capData.FileName = fileName
	}
	// check whether empty set
	if count == 0 {
		return nil, http.StatusBadRequest
	}
	return &capData, http.StatusOK
}
