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
	responseData, status := selectCapData(userId)

	log.Printf("[response] %d %s", status, responseData)
	return c.JSON(status, responseData)
}

func selectCapData(userId string) ([]*responseForGET, int) {
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

	response := make([]*responseForGET, 0)
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
		responseData := responseForGET{}
		responseData.DataId = dataId
		responseData.UserId = userId
		responseData.DataName = dataName
		responseData.DataSummary = dataSummary
		responseData.CreatedAt = createdAt
		responseData.FileName = fileName

		response = append(response, &responseData)
	}
	// check whether empty set
	if count == 0 {
		return nil, http.StatusBadRequest
	}
	return response, http.StatusOK
}
