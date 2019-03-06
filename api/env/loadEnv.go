package env

func LoadEnv() {
    err := godotenv.Load()
    if err != nil {
        log.Printf("Error loading .env file")
    }
}