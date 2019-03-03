package users

import (
	"log"
	"os"
	"time"
)

type UsersTable struct {
	UserId		 int
	Name		 string
	Email		 string
	Password	 string
	CreatedAt	 string
	UpdatedAt	 string
	IsLocked	 bool
	FailureCount int
	UnlockedAt	 string
}

func strToTime(str string) time.Time {
	t, err := time.Parse("2006-01-02 15:04:05", str)
	if err != nil {
		log.Printf("users/strToTime: err = %s", err)
		os.Exit(1)
	}
	return t
}
