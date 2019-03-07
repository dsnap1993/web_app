package db

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

type CapDataTable struct {
	DataId		 int
	UserId		 int
	DataName	 string
	DataSummary	 string
	CreatedAt	 string
	FileName	 string
}