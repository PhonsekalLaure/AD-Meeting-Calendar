CREATE TABLE IF NOT EXISTS meetings (
    id INT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    title VARCHAR(50) NOT NULL,
    description TEXT,
    schedule TIMESTAMP NOT NULL,
    location VARCHAR(50) NOT NULL,
    created_by INT REFERENCES users(id)
);

COMMENT ON TABLE meetings IS 'Table for storing meeting information including schedule, location, and creator.';