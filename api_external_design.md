# User Authentication

    POST /webapi/v0/login.json

## Request Parameters
    email: String
    password: String

## Status Code
    200

## Response
    {
        user_id: Integer,
        email: String,
        name: String
    }

# Creating a new user

    POST /webapi/v0/user.json

## Request Parameters
    name: String
    email: String
    password: String

## Status Code
    201

## Response
    {
        user_id: Integer,
        name: String,
        email: String
    }

# Editing a user

    PUT /webapi/v0/user.json

## Request Parameters
    user_id: Integer
    name: String
    email: String
    password: String

## Status Code
    200

## Response
    {
        user_id: Integer,
        name: String,
        email: String
    }

# Getting capture data

    GET /webapi/v0/capture_data.json

## Request Parameters
    user_id: Integer

## Status Code
    200

## Response
    [
        {
            data_id: Integer,
            user_id: Integer,
            data_name: String,
            data_summary: String,
            created_at: String,
            file_name: String
        },
        {
        ...
        }
    ]

# Creating new capture data

    POST /webapi/v0/capture_data.json

## Request Parameters
    user_id: Integer
    data_name: String
    data_summary: String

## Status Code
    201

## Response
    {
        data_id: Integer,
        data_name: String,
        data_summary: String,
        file_name: String,
        created_at: String
    }

# Editing name or summary of capture data

    PUT /webapi/v0/capture_data.json

## Request Parameters
    data_id: Integer
    data_name: String
    data_summary: String
    file_name: String

## Status Code
    200

## Response
    {
        data_id: Integer,
        data_name: String,
        data_summary: String
    }

# Deleting capture data

    DELETE /webapi/v0/capture_data.json

## Request Parameters
    data_id: Integer

## Status Code
    200

## Response
    {
        data_id: Integer
    }
