package users

import (
	"github.com/labstack/echo"
)

type User struct {
	UserId 		int		`json:"user_id"`
	Email 		string 	`json:"email"`
	Password 	string 	`json:"password"`
}

type UsersTable struct {
	UserId		 int
	Name		 string
	Email		 string
	Password	 string
	CreatedAt	 string
	UpdatedAt	 string
	IsLocked	 int
	FailureCount int
	UnlockedAt	 string
}
