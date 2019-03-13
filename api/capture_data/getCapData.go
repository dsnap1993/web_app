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
	dataId := c.QueryParam("data_id")
	requestParams := map[string]string {
		"user_id": userId,
		"data_id": dataId,
	}

	responseData, status := selectCapData(requestParams)

	log.Printf("[response] %d %s", status, responseData)
	return c.JSON(status, responseData)
}

func selectCapData(requestParams map[string]string) ([]*responseForGET, int) {
	dbConn, dbErr := db.ConnectDB()
	if dbErr != nil {
		log.Printf("capture_data/selectCapData: dbErr = %s", dbErr)
		os.Exit(1)
	}
	defer dbConn.Close()

	sql := "SELECT * FROM capture_data WHERE user_id="

	// check whether being set param, data_id
	if requestParams["data_id"] == "" {
		sql = sql + requestParams["user_id"]
	} else {
		sql = sql + requestParams["user_id"] + " AND data_id=" + requestParams["data_id"]
	}

	data, err := dbConn.Query(sql)
	if err != nil {
		log.Printf("capture_data/selectCapData: err = %s", err)
		return nil, http.StatusInternalServerError
	}

	response := make([]*responseForGET, 0)
	
	for data.Next() {
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
	return response, http.StatusOK
}
