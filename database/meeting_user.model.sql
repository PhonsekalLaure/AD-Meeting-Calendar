CREATE TABLE IF NOT EXISTS meeting_users (
    meeting_id INTEGER NOT NULL REFERENCES meetings (id),
    user_id INTEGER NOT NULL REFERENCES users (id),
    role VARCHAR(50) NOT NULL,
    PRIMARY KEY (meeting_id, user_id)
);

COMMENT ON TABLE meeting_users IS 'Associative table linking users to meetings, specifying each user\'s role in the meeting.';